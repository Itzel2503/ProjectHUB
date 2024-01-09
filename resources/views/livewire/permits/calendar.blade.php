<div>
    <div id='calendar-container' wire:ignore>
        <div class="m-5" id='calendar'></div>
    </div>
    
    <div id="modal_create_edit" class="top-20 left-0 z-50 max-h-full overflow-y-auto hidden">      
        <div class="flex justify-center h-screen items-center top-0 opacity-80 left-0 z-30 w-full h-full fixed bg-no-repeat bg-cover bg-gray-500"></div>
        <div class="flex text:md justify-center h-screen items-center top-0 left-0 z-40 w-full h-full fixed">
            <div class="flex flex-col w-2/4 sm:w-5/6 lg:w-3/5  mx-auto rounded-lg  shadow-xl overflow-y-auto " style="max-height: 90%;">
                <div class="flex flex-row justify-between px-6 py-4 bg-main-fund text-white rounded-tl-lg rounded-tr-lg">
                    <h2 class="text-xl text-secondary font-medium title-font  w-full border-l-4 border-secondary-fund pl-4 py-2">Nuevo permiso</h2>
                    <svg id="close_modal" class="w-6 h-6 cursor-pointer text-black hover:stroke-2" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M18 6l-12 12"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                </div>
                
                {{-- INPUT DE FECHA SELECCIONADA --}}
                <input wire:model.defer="date" wire:ignore type="text" name="date" id="date_input">

                <div class="flex flex-col sm:flex-row px-6 py-2 bg-main-fund overflow-y-auto text-sm">
                    <div x-data="{ selectedPermit: 0 }" class="w-3/5 sm:w-full md-3/4 mb-5 mt-5 flex flex-col">
                        <div id="permit0" class="-mx-3 md:flex mb-6">
                            <div class="md:w-1/2 flex flex-col px-3">
                                <h5 class="inline-flex font-semibold mb-3" for="name">
                                    Tipo de permiso a solicitar:
                                </h5>
                                <select x-model="selectedPermit" name="type_permits" id="type_permits" class="leading-snug border border-gray-400 block w-3/4 appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                                    <option selected value="0">Selecciona...</option>
                                    @foreach ($permits as $permit)
                                        <option @click="open = true" value="{{ $permit->id }}">{{ $permit->name }}</option>
                                    @endforeach
                                </select>                                
                            </div>
                        </div>

                        {{-- PERMIT1 --}}
                        <div x-show="selectedPermit == 1" @click.outside="selectedPermit = 0">
                            <div class="-mx-3 md:flex mb-6">
                                <div class="md:w-1/2 flex flex-col px-3 mb-6 md:mb-0">
                                    <div class="relative z-0 w-full group flex items-center">
                                        <h5 class="relative z-0 group flex items-center mr-5 font-semibold" for="name">
                                            Fecha seleccionada <p class="text-red">*</p>
                                        </h5>
                                        <label class="date text-sm inline-flex font-semibold"></label>
                                    </div>
                                </div>
                                <div class="md:w-1/2 flex flex-col px-3">
                                    <h5 class="inline-flex font-semibold" for="name">
                                        Motivo <p class="text-red">*</p>
                                    </h5>
                                    <select wire:model.defer="reason" name="reason" id="reason" class="fields1 leading-snug border border-gray-400 block w-3/4 appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                                        <option selected>Selecciona...</option>
                                        @foreach($motiveOptions as $value => $motiveOption)
                                            <option value="{{ $value }}">{{ $motiveOption }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="-mx-3 md:flex mb-6">
                                <div class="md:w-1/2 flex flex-col px-3 mb-6 md:mb-0">
                                    <h5 class="inline-flex font-semibold" for="name">
                                        Actividades compromiso a realizar en Home Office <p class="text-red">*</p>
                                    </h5>
                                    <textarea wire:model.defer="activities" type="text" rows="10" placeholder="" name="activities" id="activities" class="fields1 leading-snug border border-gray-400 block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto"></textarea> 
                                </div>
                                <div class="md:w-1/2 flex flex-col px-3 mb-6 md:mb-0">
                                    <h5 class="inline-flex font-semibold" for="name">
                                        Persona a quien delega actividades durante su ausencia en caso de requerise alguna actidad en oficina <p class="text-red">*</p>
                                    </h5>
                                    <select wire:model.defer="delegate_activities" name="delegate_activities" id="delegate_activities" class="fields1 leading-snug border border-gray-400 block w-3/4 appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                                        <option selected>Selecciona...</option>
                                        @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="flex justify-center items-center py-6 bg-main-fund">
                                <button wire:click="createPermit1" id="btn_save" class="px-4 py-2 text-white font-semibold bg-secondary-fund hover:bg-secondary rounded cursor-pointer"> Guardar </button>
                            </div>
                        </div>

                        {{-- PERMIT2 --}}
                        <div x-show="selectedPermit == 2" @click.outside="selectedPermit = 0">
                            <div class="-mx-3 md:flex mb-6">
                                <div class="md:w-1/2 flex flex-col px-3 mb-6 md:mb-0">
                                    <div class="relative z-0 w-full group flex items-center">
                                        <h5 class="relative z-0 group flex items-center mr-5 font-semibold" for="name">
                                            Fecha seleccionada <p class="text-red">*</p>
                                        </h5>
                                        <label class="date text-sm inline-flex font-semibold"></label>
                                    </div>
                                </div>
                                <div class="md:w-1/2 flex flex-col px-3">
                                    <h5 class="inline-flex font-semibold" for="name">
                                        Selecciona una opción <p class="text-red">*</p>
                                    </h5>
                                    <select  name="type" id="type" class="leading-snug border border-gray-400 block w-3/4 appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                                        <option selected>Selecciona...</option>
                                        @foreach($typeHours as $value => $typeHour)
                                            <option value="{{ $value }}">{{ $typeHour }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="-mx-3 md:flex mb-6">
                                <div class="md:w-1/2 flex flex-col px-3 mb-6 md:mb-0">
                                    <h5 class="inline-flex font-semibold" for="name">
                                        Motivo <p class="text-red">*</p>
                                    </h5>
                                    <select  name="reason" id="reason" class="leading-snug border border-gray-400 block w-3/4 appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                                        <option selected>Selecciona...</option>
                                        @foreach($motiveOptions as $value => $motiveOption)
                                            <option value="{{ $value }}">{{ $motiveOption }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="md:w-1/2 flex flex-col px-3 mb-6 md:mb-0">
                                    <h5 class="inline-flex font-semibold" for="name">
                                        Horas a tomar <p class="text-red">*</p>
                                    </h5>
                                    <div class="relative z-0 w-full group flex justify-between items-center">
                                        <select  name="take_hours" id="take_hours" class="leading-snug border border-gray-400 block w-1/4 appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                                            <option selected>Selecciona...</option>
                                            @foreach ($takeHours as $key => $takeHour)
                                            <option value="{{ $key }}">{{ $takeHour }}</option>
                                            @endforeach
                                            <option value="other">Otro</option>
                                        </select>
                                        <input type="text" name="other_take_hours" id="other_take_hours" placeholder="Especifica" class="hidden leading-snug border border-gray-400 block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto" oninput="validateNumberInput(this)">
                                    </div>
                                </div>
                            </div>
                            <div class="-mx-3 md:flex mb-6">
                                <div class="md:w-1/2 flex flex-col px-3 mb-6 md:mb-0">
                                    <h5 class="inline-flex font-semibold" for="name">
                                        Persona a quien delega actividades durante su ausencia en caso de requerise alguna actidad en oficina <p class="text-red">*</p>
                                    </h5>
                                    <select  name="delegate_activities" id="delegate_activities" class="leading-snug border border-gray-400 block w-3/4 appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                                        <option selected>Selecciona...</option>
                                        @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="flex justify-center items-center py-6 bg-main-fund">
                                <button id="btn_save" class="px-4 py-2 text-white font-semibold bg-secondary-fund hover:bg-secondary rounded cursor-pointer"> Guardar </button>
                            </div>
                        </div>

                        {{-- PERMIT3 --}}
                        <div x-show="selectedPermit == 3" @click.outside="selectedPermit = 0">
                            <div class="-mx-3 md:flex mb-6">
                                <div class="md:w-1/2 flex flex-col px-3 mb-6 md:mb-0">
                                    <div class="relative z-0 w-full group flex items-center">
                                        <h5 class="relative z-0 group flex items-center mr-5 font-semibold" for="name">
                                            Fecha seleccionada <p class="text-red">*</p>
                                        </h5>
                                        <label class="date text-sm inline-flex font-semibold"></label>
                                    </div>
                                </div>
                                <div class="md:w-1/2 flex flex-col px-3 mb-6 md:mb-0">
                                    <h5 class="inline-flex font-semibold" for="name">
                                        Motivo <p class="text-red">*</p>
                                    </h5>
                                    <select  name="reason" id="reason" class="leading-snug border border-gray-400 block w-3/4 appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                                        <option selected>Selecciona...</option>
                                        @foreach($motiveOptions as $value => $motiveOption)
                                            <option value="{{ $value }}">{{ $motiveOption }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="-mx-3 md:flex mb-6">
                                <div class="md:w-1/2 flex flex-col px-3 mb-6 md:mb-0">
                                    <h5 class="inline-flex font-semibold" for="name">
                                        Permiso <p class="text-red">*</p>
                                    </h5>
                                    <div class="relative z-0 w-full group flex justify-between items-center">
                                        <select  name="salary" id="salary" class="leading-snug border border-gray-400 block w-1/4 appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                                            <option selected>Selecciona...</option>
                                            @foreach ($salaryPermits as $key => $salaryPermit)
                                            <option value="{{ $key }}">{{ $salaryPermit }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="md:w-1/2 flex flex-col px-3 mb-6 md:mb-0">
                                    <h5 class="inline-flex font-semibold" for="name">
                                        Persona a quien delega actividades durante su ausencia en caso de requerise alguna actidad en oficina <p class="text-red">*</p>
                                    </h5>
                                    <select  name="delegate_activities" id="delegate_activities" class="leading-snug border border-gray-400 block w-3/4 appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                                        <option selected>Selecciona...</option>
                                        @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="flex justify-center items-center py-6 bg-main-fund">
                                <button id="btn_save" class="px-4 py-2 text-white font-semibold bg-secondary-fund hover:bg-secondary rounded cursor-pointer"> Guardar </button>
                            </div>
                        </div>

                        {{-- PERMIT4 --}}
                        <div x-show="selectedPermit == 4" @click.outside="selectedPermit = 0">
                            <div class="-mx-3 md:flex mb-6">
                                <div class="md:w-1/2 flex flex-col px-3 mb-6 md:mb-0">
                                    <div class="relative z-0 w-full group flex items-center">
                                        <h5 class="relative z-0 group flex items-center mr-5 font-semibold" for="name">
                                            Fecha seleccionada de inicio <p class="text-red">*</p>
                                        </h5>
                                        <label class="date text-sm inline-flex font-semibold"></label>
                                    </div>
                                </div>
                                <div class="md:w-1/2 flex flex-col px-3 mb-6 md:mb-0">
                                    <div class="relative z-0 w-full group flex items-center">
                                        <h5 class="relative z-0 group flex items-center mr-5 w-2/4 font-semibold" for="name">
                                            Fecha de termino <p class="text-red">*</p>
                                        </h5>
                                        <input type="date" name="date_end" id="date_end" class="leading-snug border border-gray-400 block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto" />
                                    </div>
                                </div>
                            </div>
                            <div class="-mx-3 md:flex mb-6">
                                <div class="md:w-1/2 flex flex-col px-3 mb-6 md:mb-0">
                                    <h5 class="inline-flex font-semibold" for="name">
                                        Persona a quien delega actividades durante su ausencia en caso de requerise alguna actidad en oficina <p class="text-red">*</p>
                                    </h5>
                                    <select  name="delegate_activities" id="delegate_activities" class="leading-snug border border-gray-400 block w-3/4 appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                                        <option selected>Selecciona...</option>
                                        @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="flex justify-center items-center py-6 bg-main-fund">
                                <button id="btn_save" class="px-4 py-2 text-white font-semibold bg-secondary-fund hover:bg-secondary rounded cursor-pointer"> Guardar </button>
                            </div>
                        </div>

                        {{-- PERMIT5 --}}
                        <div x-show="selectedPermit == 5" @click.outside="selectedPermit = 0">
                            <div class="-mx-3 md:flex mb-6">
                                <div class="md:w-1/2 flex flex-col px-3 mb-6 md:mb-0">
                                    <div class="relative z-0 w-full group flex items-center">
                                        <h5 class="relative z-0 group flex items-center mr-5" for="name">
                                            Fecha seleccionada <p class="text-red">*</p>
                                        </h5>
                                        <label class="date text-sm inline-flex font-semibold"></label>
                                    </div>
                                </div>
                                <div class="md:w-1/2 flex flex-col px-3 mb-6 md:mb-0">
                                    <div class="relative z-0 w-full group flex items-center">
                                        <h5 class="relative z-0 group flex items-center mr-5 w-2/4" for="name">
                                            Horas a registrar <p class="text-red">*</p>
                                        </h5>
                                        <input type="text" name="hours" id="hours" placeholder="Ej. 4" class="leading-snug border border-gray-400 block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto" oninput="validateNumberInput(this)">
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-center items-center py-6 bg-main-fund">
                                <button id="btn_save" class="px-4 py-2 text-white font-semibold bg-secondary-fund hover:bg-secondary rounded cursor-pointer"> Guardar </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src='fullcalendar/core/index.global.js'></script>
    <script src='fullcalendar/core/locales/es.global.js'></script>
    <script>
        document.addEventListener('livewire:load', function() {                
            var calendarEl = document.getElementById('calendar');

            var data = @this.events;

            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'es',
                timeZone: 'UTC',
                initialView: 'dayGridMonth',
                events: '/api/demo-feeds/events.json',

                eventColor: "#4faead",
                eventTextColor: '#000000',

                editable: true,
                selectable: true,
                displayEventTime: false,
                droppable: true, // this allows things to be dropped onto the calendar

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
                    end: ''
                },

                dateClick(info) {
                    let modalCreateEdit = document.getElementById('modal_create_edit');
                    modalCreateEdit.classList.remove('hidden');
                    modalCreateEdit.classList.add('block');
                    console.log(info);
                    let dateElements = document.querySelectorAll('.date');
                    let dateInputs = document.getElementById('date_input');
                    
                    dateElements.forEach(function(element) {
                        element.innerText = info.dateStr;
                    });
                    dateInputs.value = info.dateStr;

                    var title = prompt('Enter Event Title');
                    var date = new Date(info.dateStr + 'T00:00:00');
                    if (title != null && title != '') {
                        calendar.createPermit({
                            title: title,
                            start: date,
                            allDay: true
                        });
                        var eventAdd = {
                            title: title,
                            start: date
                        };
                        @this.createPermit(eventAdd);
                        alert('Great. Now, update database...');
                    } else {
                        alert('Event Title Is Required');
                    }
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

                drop: function(info) {
                    // is the "remove after drop" checkbox checked?
                    if (checkbox.checked) {
                        // if so, remove the element from the "Draggable Events" list
                        info.draggedEl.parentNode.removeChild(info.draggedEl);
                    }
                },
                eventDrop: info => @this.eventDrop(info.event, info.oldEvent),
                loading: function(isLoading) {
                    if (!isLoading) {
                        // Reset custom events
                        this.getEvents().forEach(function(e) {
                            if (e.source === null) {
                                e.remove();
                            }
                        });
                    }
                },
                events: [
                    {
                        title: 'All Day Event',
                        start: '2024-01-01'
                    },
                    {
                        title: 'Long Event',
                        start: '2024-01-07',
                        end: '2024-01-10',
                        color: 'purple' // override!
                    },
                    {
                        title: 'Conference',
                        start: '2024-01-07',
                        end: '2024-01-10',
                        color: 'yellow' // override!
                    },
                    {
                        groupId: '999',
                        title: 'Repeating Event',
                        start: '2024-01-09T16:00:00'
                    },
                    {
                        groupId: '999',
                        title: 'Repeating Event',
                        start: '2024-01-16T16:00:00'
                    },
                    {
                        title: 'Meeting',
                        start: '2024-01-12T10:30:00',
                        end: '2024-01-12T12:30:00'
                    },
                    {
                        title: 'Lunch',
                        start: '2024-01-12T12:00:00'
                    },
                    {
                        title: 'Meeting',
                        start: '2024-01-12T14:30:00',
                        color: 'purple' // override!
                    },
                    {
                        title: 'Click for Google',
                        url: 'https://google.com/',
                        start: '2024-01-28'
                    }
                ]
            });
            calendar.render();
            @this.on(`refreshCalendar`, () => {
                calendar.refetchEvents()
            });
        });
    </script>    
</div>
