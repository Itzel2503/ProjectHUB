<div>
    <div class="px-4 py-4 sm:rounded-lg">
        <div class="flex gap-2 text-sm md:flex-row lg:text-base">
            <div class="mb-2 inline-flex h-10 w-2/6 bg-transparent px-2 md:mx-3 md:px-0">
                <label class="inputs" wire:ignore>
                    <div class="inline-block flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-week mr-2">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                            <path d="M16 3v4" />
                            <path d="M8 3v4" />
                            <path d="M4 11h16" />
                            <path d="M8 14v4" />
                            <path d="M12 14v4" />
                            <path d="M16 14v4" />
                        </svg>
                        <input disabled type="text" id="daterange" class="h-0 w-0 border-none bg-gray-100 p-0"
                            wire:model="dateRange" onchange="Livewire.emit('setDate', this.value)">
                        <span class="text-base" id="spanDate"></span>
                    </div>
                </label>
            </div>
            <div class="mb-2 inline-flex h-12 w-2/6 bg-transparent px-2 md:mx-3 md:px-0">
                Qty días. {{ $qty }}
            </div>
        </div>
        <div id="chart" class="mt-5"></div>
    </div>
    @push('js')
        <script>
            document.addEventListener('livewire:load', function() {
                // FILTRO FECHA
                var today = moment();
                // var dayOfWeek = today.day();
                // var startOfWeek = moment().subtract(dayOfWeek - 1, 'days');
                // var endOfWeek = moment().add(7 - dayOfWeek, 'days');
                var startOfMonth = today.startOf('month').format('YYYY-MM-DD');
                var endOfMonth = today.endOf('month').format('YYYY-MM-DD');

                var start = startOfMonth;
                var end = endOfMonth;

                $('#daterange').daterangepicker({
                    locale: {
                        format: 'YYYY-MM-DD'
                    },
                    autoUpdateInput: false,
                    startDate: start,
                    endDate: end,
                    ranges: {
                        'Hoy': [moment(), moment()],
                        'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
                        'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
                        'Este mes': [moment().startOf('month'), moment().endOf('month')],
                        'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                            'month').endOf('month')]
                    }
                }, function(start, end, label) {
                    $('#daterange').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                    @this.set('dateRange', start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                });

                $('#spanDate').html(start + ' - ' + end);

                $('#daterange').on('apply.daterangepicker', function(ev, picker) {
                    $('#spanDate').html(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                        'YYYY-MM-DD'));
                    Livewire.emit('setDate', picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate
                        .format('YYYY-MM-DD'));
                });
                // GRAFICA
                var chart;

                function renderChart(series, categories, totalEffortPoints) {
                    if (chart) {
                        chart.destroy();
                    }

                    var options = {
                        series: series,
                        chart: {
                            type: 'bar',
                            height: 'auto',
                            stacked: true,
                        },
                        colors: ['rgb(77, 124, 15)', 'rgb(250, 204, 21)', 'rgb(220, 38, 38)', 'rgb(59, 130, 246)',
                            'rgb(243, 244, 246)'
                        ],
                        plotOptions: {
                            bar: {
                                horizontal: true,
                                dataLabels: {
                                    position: 'top',
                                    total: {
                                        enabled: true,
                                        formatter: function(val, opt) {
                                            return totalEffortPoints[opt.dataPointIndex];
                                        },
                                        offsetX: 0,
                                        style: {
                                            fontSize: '13px',
                                            fontWeight: 900,
                                            color: function({
                                                seriesIndex
                                            }) {
                                                return seriesIndex === 4 ? '#000' : '#fff';
                                            }
                                        }
                                    }
                                }
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                colors: ['#fff', '#fff', '#fff', '#fff', '#000']
                            }
                        },
                        stroke: {
                            width: 1,
                            colors: ['#fff']
                        },
                        xaxis: {
                            categories: categories,
                            labels: {
                                formatter: function(val) {
                                    return val;
                                }
                            }
                        },
                        yaxis: {
                            title: {
                                text: undefined
                            }
                        },
                        tooltip: {
                            y: {
                                formatter: function(val) {
                                    return val;
                                }
                            }
                        },
                        fill: {
                            opacity: 1
                        },
                        legend: {
                            position: 'top',
                            horizontalAlign: 'left',
                            offsetX: 40
                        }
                    };

                    chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();
                }

                livewire.on('updateChart', function() {
                    var series = @json($series);
                    var categories = @json($categories);
                    var totalEffortPoints = @json($totalEffortPoints);
                    renderChart(series, categories, totalEffortPoints);
                });

                var initialSeries = @json($series);
                var initialCategories = @json($categories);
                var initialTotalEffortPoints = @json($totalEffortPoints);
                renderChart(initialSeries, initialCategories, initialTotalEffortPoints);
            });
        </script>
    @endpush
</div>
