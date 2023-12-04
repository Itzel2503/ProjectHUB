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

{{-- MODAL CREATE --}}
@include('/activitycontrol/permits/modal/create')

<script src='fullcalendar/core/index.global.js'></script>
<script src='fullcalendar/core/locales/es.global.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'es',
            timeZone: 'UTC',
            initialView: 'dayGridMonth',
            events: '/api/demo-feeds/events.json',

            eventColor: "#4faead",
            eventTextColor: '#000000',

            editable: true,
            selectable: true,

            eventTimeFormat: { // like '14:30:00'
                hour: '2-digit',
                minute: '2-digit',
                meridiem: false
            },

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

            eventClick: function(info) {
                var eventObj = info.event;

                if (eventObj.url) {
                    alert(
                    'Clicked ' + eventObj.title + '.\n' +
                    'Will open ' + eventObj.url + ' in a new tab'
                    );

                    window.open(eventObj.url);

                    info.jsEvent.preventDefault(); // prevents browser from following link in current tab.
                } else {
                    alert('Clicked ' + eventObj.title);
                }
            },

            dateClick: function(info) {
                let modalCreateEdit = document.getElementById('modal_create_edit');
                modalCreateEdit.classList.remove('hidden');
                modalCreateEdit.classList.add('block');
                console.log(info);
                let date = document.getElementById('date').innerText = info.dateStr;

                // var dateStr = prompt('Seleccionaste' + info.startStr + 'a' + info.endStr);
                // var date = new Date(dateStr + 'T00:00:00'); // will be in local time

                /* if (!isNaN(date.valueOf())) { // valid?
                    calendar.addEvent({
                    title: 'dynamic event',
                    start: date,
                    allDay: true
                    });
                    alert('Great. Now, update your database...');
                } else {
                    alert('Invalid date.');
                } */
            },
            
            events: [
                {
                    title: 'All Day Event',
                    start: '2023-12-01'
                },
                {
                    title: 'Long Event',
                    start: '2023-12-07',
                    end: '2023-12-10',
                    color: 'purple' // override!
                },
                {
                    title: 'Conference',
                    start: '2023-12-07',
                    end: '2023-12-10',
                    color: 'yellow' // override!
                },
                {
                    groupId: '999',
                    title: 'Repeating Event',
                    start: '2023-12-09T16:00:00'
                },
                {
                    groupId: '999',
                    title: 'Repeating Event',
                    start: '2023-12-16T16:00:00'
                },
                {
                    title: 'Meeting',
                    start: '2023-12-12T10:30:00',
                    end: '2023-12-12T12:30:00'
                },
                {
                    title: 'Lunch',
                    start: '2023-12-12T12:00:00'
                },
                {
                    title: 'Meeting',
                    start: '2023-12-12T14:30:00',
                    color: 'purple' // override!
                },
                {
                    title: 'Click for Google',
                    url: 'https://google.com/',
                    start: '2023-12-28'
                }
            ]
        });

        calendar.render();
    });
</script>
@endsection