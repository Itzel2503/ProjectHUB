@extends('layouts.header')

@section('usuarios')
<span class="absolute inset-y-0 left-0 w-1 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
@endsection

@section('black2')
text-yellow
@endsection

@section('content')
<div class="mt-8">
    <div class=" w-full mx-auto">
        <div class="mt-5 md:mt-0 md:col-span-1">
            <div class="px-4 py-5 space-y-6 sm:p-6 w-full bg-main-fund">
                <h1 class="inline-flex w-full text-base font-semibold text-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-run" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M13 4m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                        <path d="M4 17l5 1l.75 -1.5"></path>
                        <path d="M15 21l0 -4l-4 -3l1 -6"></path>
                        <path d="M7 12l0 -3l5 -1l3 3l3 1"></path>
                    </svg>
                    <span class="ml-4">Permisos</span>
                </h1>
            </div>
            <div class="m-5" id='calendar'></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            timeZone: 'UTC',
            initialView: 'dayGridMonth',
            events: '/api/demo-feeds/events.json',
            editable: true,
            selectable: true,
            headerToolbar: {
                start: 'dayGridMonth,timeGridWeek,timeGridDay',
                center: 'title',
                end: 'prev,next',
            },
            footerToolbar: {
                start: '',
                center: '',
                end: 'prev,next'
            },
        });

        calendar.render();
    });
</script>
@endsection