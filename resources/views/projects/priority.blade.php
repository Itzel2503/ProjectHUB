@extends('layouts.header')

@section('content')
    <div class="mx-auto mt-4 w-full">
        <div class="w-full space-y-6 px-4 py-3">
            <h1 class="tiitleHeader">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
                    class="icon icon-tabler icons-tabler-filled icon-tabler-star">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z" />
                </svg>
                <span class="ml-4 text-xl">Prioridades</span>
            </h1>
        </div>
        <div class="flex justify-center mb-5">
            <h5 class="text-lg font-bold bg-yellow-400">{{ $weekText }}</h5>
        </div>
        <div class="w-full xl:flex xl:justify-center">
            <div id="chartActivo" class="mt-5 w-full px-5"></div>
            <div id="chartSoporte" class="mt-5 w-full px-5"></div>
        </div>
        <livewire:projects.priorities-coments/>

        @livewireScripts
        @stack('js')
        <script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Datos para el gráfico de Proyectos Activos
            var activoNames = @json($activo->pluck('name'));
            var activoPriorities = @json($activo->pluck('priority'));
            var activoLeaderNames = @json($activo->pluck('leader_name'));
            var activoProgrammerNames = @json($activo->pluck('programmer_name'));
            // Ordenar los datos por prioridad
            var activoData = activoNames.map((name, index) => ({
                name: name,
                priority: activoPriorities[index],
                leader_name: activoLeaderNames[index],
                programmer_name: activoProgrammerNames[index]
            })).sort((a, b) => a.priority - b.priority);
            // Función para asignar colores según prioridad
            function getColor(priority) {
                switch (priority) {
                    case 1:
                        return '#F19632';
                    case 2:
                        return '#F6B36B';
                    case 3:
                        return '#FBC568';
                    case 4:
                        return '#D5CF6F';
                    case 5:
                        return '#ABC878';
                    case 6:
                        return '#81C181';
                    case 7:
                        return '#57BB8A';
                    default:
                        return '#57BB8A';
                }
            }

            var optionsActivo = {
                series: [{
                    name: "Prioridad",
                    data: activoData.map(item => item.priority),
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                },
                plotOptions: {
                    bar: {
                        borderRadius: 0,
                        horizontal: true,
                        distributed: true,
                        barHeight: '80%',
                        isFunnel: true,
                    },
                },
                
                colors: activoData.map(item => getColor(item.priority)),
                dataLabels: {
                    enabled: true,
                    formatter: function(val, opt) {
                        return opt.w.globals.labels[opt.dataPointIndex];
                    },
                    style: {
                        colors: ['#000']
                    }
                },
                title: {
                    text: 'Activos',
                    align: 'left',
                    style: {
                        fontSize:  '18px',
                        fontWeight:  'bold',
                    },
                },
                xaxis: {
                    categories: activoData.map(item =>
                        `${item.name} - K${item.priority} - Líder: ${item.leader_name} - Desarrollo: ${item.programmer_name}`
                        ),
                },
                legend: {
                    show: false,
                },
            };

            var chartActivo = new ApexCharts(document.querySelector("#chartActivo"), optionsActivo);
            chartActivo.render();
            // Datos para el gráfico de Proyectos Soporte
            var soporteNames = @json($soporte->pluck('name'));
            var soportePriorities = @json($soporte->pluck('priority'));
            var soporteLeaderNames = @json($activo->pluck('leader_name'));
            var soporteProgrammerNames = @json($activo->pluck('programmer_name'));
            // Ordenar los datos por prioridad
            var soporteData = soporteNames.map((name, index) => ({
                name: name,
                priority: soportePriorities[index],
                leader_name: soporteLeaderNames[index],
                programmer_name: soporteProgrammerNames[index]
            })).sort((a, b) => a.priority - b.priority);

            var optionsSoporte = {
                series: [{
                    name: "Prioridad",
                    data: soporteData.map(item => item.priority),
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                },
                plotOptions: {
                    bar: {
                        borderRadius: 0,
                        horizontal: true,
                        distributed: true,
                        barHeight: '80%',
                        isFunnel: true,
                    },
                },
                colors: soporteData.map(item => getColor(item.priority)),
                dataLabels: {
                    enabled: true,
                    formatter: function(val, opt) {
                        return opt.w.globals.labels[opt.dataPointIndex];
                    },
                    style: {
                        colors: ['#000']
                    }
                },
                title: {
                    text: 'Soporte',
                    align: 'left',
                    style: {
                        fontSize:  '18px',
                        fontWeight:  'bold',
                    },
                },
                xaxis: {
                    categories: soporteData.map(item =>
                        `${item.name} - K${item.priority} - Líder: ${item.leader_name} \ Develoment: ${item.programmer_name}`
                        ),
                },
                legend: {
                    show: false,
                },
            };

            var chartSoporte = new ApexCharts(document.querySelector("#chartSoporte"), optionsSoporte);
            chartSoporte.render();
        });
    </script>
@endsection
