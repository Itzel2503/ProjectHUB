@extends('layouts.header')

@section('content')
    <div class="mx-auto mt-4 w-full">
        <div class="w-full space-y-6 px-4 py-3">
            <h1 class="tiitleHeader">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-list-search">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M15 15m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                    <path d="M18.5 18.5l2.5 2.5" />
                    <path d="M4 6h16" />
                    <path d="M4 12h4" />
                    <path d="M4 18h4" />
                </svg>
                <span class="ml-4 text-xl">Actividades y Reportes</span>
            </h1>
        </div>
        <div id="chart1" class="my-5 hidden md:block"></div>
        <div id="chart2" class="my-5 block md:hidden"></div>
        <livewire:activities-reports.activities-reports>

            @livewireScripts
            @stack('js')
            <script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>
            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    </div>
    <script>
        // GRAFICA
        document.addEventListener('DOMContentLoaded', function() {
            // Datos para el gráfico de Proyectos Activos
            var categories = @json($categories);
            var series = @json($series);
            var totalEffortPoints = @json($totalEffortPoints);

            var options1 = {
                series: series,
                chart: {
                    type: 'bar',
                    height: '135px',
                    stacked: true,
                },
                colors: ['rgb(77, 124, 15)', 'rgb(250, 204, 21)', 'rgb(220, 38, 38)', 'rgb(59, 130, 246)',
                    'rgb(243, 244, 246)', 'rgb(0,0,0)'
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
                title: {
                    text: 'Story Points por mes'
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

            var options2 = {
                series: series,
                chart: {
                    type: 'bar',
                    height: 'auto',
                    stacked: true,
                },
                colors: ['rgb(77, 124, 15)', 'rgb(250, 204, 21)', 'rgb(220, 38, 38)', 'rgb(59, 130, 246)',
                    'rgb(243, 244, 246)', 'rgb(0,0,0)'
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
                title: {
                    text: 'Story Points por mes'
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

            chart = new ApexCharts(document.querySelector("#chart1"), options1);
            chart.render();

            chart = new ApexCharts(document.querySelector("#chart2"), options2);
            chart.render();
        });
    </script>
@endsection
