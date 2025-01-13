@extends('layouts.header')
@section('content')
    <style>
        .emoji-item {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            cursor: pointer;
            transition: transform 0.2s ease;
            border: 2px solid transparent;
            border-radius: 8px;
            padding: 5px;
        }
        .emoji-item img {
            max-width: 100%;
            pointer-events: none;
        }
        .emoji-item.selected {
            transform: scale(1.4);
            border-color: #007bff;
        }
        .emoji-item:hover {
            transform: scale(1.2);
        }
        .hidden-checkbox {
            display: none;
        }
        #calendar {
            max-width: 1100px;
            margin-left: 1.25rem;
            margin-right: 1.25rem;
        }
    </style>
    <div class="mx-auto mt-4 w-full">
        <div class="w-full space-y-6 px-4 py-3">
            <h1 class="tiitleHeader">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-note">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M13 20l7 -7" />
                    <path d="M13 20v-6a1 1 0 0 1 1 -1h6v-7a2 2 0 0 0 -2 -2h-12a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7" />
                </svg>
                <span class="ml-4 text-xl">Notas</span>
            </h1>
        </div>
        <div class="px-4 py-4 sm:rounded-lg">
            {{-- NAVEGADOR --}}
            <div class="flex flex-wrap justify-end text-sm lg:text-base">
                <!-- BTN NEW -->
                <div class="mb-2 inline-flex h-12 w-1/2 bg-transparent px-2 md:w-1/4 md:px-0">
                    <button id="btn-new" class="btnNuevo">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus mr-2"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        <span>Nota</span>
                    </button>
                </div>
            </div>
        </div>
        <div id='calendar'></div>
        {{-- MODAL EDIT / CREATE USER --}}
        <div id="modal-edit-create" class="left-0 top-20 z-50 hidden max-h-full overflow-y-auto">
            <div
                class="fixed left-0 top-0 z-30 flex h-screen w-full items-center justify-center bg-gray-500 bg-cover bg-no-repeat opacity-80">
            </div>
            <div class="text:md fixed left-0 top-0 z-40 flex h-screen w-full items-center justify-center">
                <div class="mx-auto flex flex-col overflow-y-auto rounded-lg md:w-2/5" style="max-height: 90%;">
                    <div class="flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-6 py-4 text-white">
                        <h3
                            class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                            Nueva nota</h3>
                        <svg id="btn-close-modal-create" class="my-2 h-6 w-6 cursor-pointer text-black hover:stroke-2"
                            xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M18 6l-12 12"></path>
                            <path d="M6 6l12 12"></path>
                        </svg>
                    </div>
                    <form id="form-new-notion" action="{{ route('notion.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modalBody">
                            <div class="md-3/4 mb-5 mt-5 flex w-full flex-col">
                                <div class="-mx-3 mb-6 flex flex-row">
                                    <div class="mb-6 flex w-full flex-col px-3">
                                        <h5 class="inline-flex font-semibold" for="file">
                                            Icono
                                        </h5>
                                        <div class="flex justify-between">
                                            <div id="emoji-container" style="display: flex; gap: 10px; cursor: pointer;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="-mx-3 mb-6 flex flex-row">
                                    <div class="mb-6 flex w-full flex-col px-3">
                                        <h5 class="inline-flex font-semibold" for="title">
                                            Título <p class="text-red-600">*</p>
                                        </h5>
                                        <input required type="text" name="title" id="title" class="inputs"
                                            placeholder="Título general de la actividad">
                                    </div>
                                </div>
                                <div class="-mx-3 mb-6 flex flex-row">
                                    <div class="mb-6 flex w-full flex-col px-3">
                                        <h5 class="inline-flex font-semibold" for="date">
                                            Fecha <p class="text-red-600">*</p>
                                        </h5>
                                        <input required type="date" name="date" id="date" class="inputs"
                                            placeholder="Título general de la actividad">
                                    </div>
                                    <div class="mb-6 flex w-full flex-col px-3">
                                        <h5 class="inline-flex font-semibold" for="repeat">
                                            Repetir
                                        </h5>
                                        <select name="repeat" id="repeat" class="inputs">
                                            <option selected value="Once">Una vez</option>
                                            <option value="Daily">Diariamente</option>
                                            <option value="Monday">Lunes</option>
                                            <option value="Tuesday">Martes</option>
                                            <option value="Wendnesday">Miercoles</option>
                                            <option value="Thursday">Jueves</option>
                                            <option value="Friday">Viernes</option>
                                            <option value="Monday - Friday">Lun a Vie</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="-mx-3 mb-6 flex flex-row">
                                    <div class="mb-6 flex w-full flex-col px-3">
                                        <h5 class="inline-flex font-semibold" for="phone">
                                            Prioridad
                                        </h5>
                                        <div class="flex justify-center gap-20">
                                            <div class="flex flex-col items-center">
                                                <input type="checkbox" name="priority1" id="priority1" value="Alto"
                                                    class="priority-checkbox border-red-600 bg-red-600"
                                                    style="height: 24px; width: 24px; accent-color: #dd4231;" />
                                                <label for="priority1" class="mt-2">Alto</label>
                                            </div>
                                            <div class="flex flex-col items-center">
                                                <input type="checkbox" name="priority2" id="priority2" value="Medio"
                                                    class="priority-checkbox border-yellow-400 bg-yellow-400"
                                                    style="height: 24px; width: 24px; accent-color: #f6c03e;" />
                                                <label for="priority2" class="mt-2">Medio</label>
                                            </div>
                                            <div class="flex flex-col items-center">
                                                <input type="checkbox" name="priority3" id="priority3" value="Bajo"
                                                    class="priority-checkbox border-secondary bg-secondary"
                                                    style="height: 24px; width: 24px; accent-color: #0062cc;" />
                                                <label for="priority3" class="mt-2">Bajo</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-6 flex w-full flex-col px-3">
                                        <h5 class="inline-flex font-semibold" for="dropdown-button-projects">
                                            Delegar
                                        </h5>
                                        <div id="dropdown-button-projects" class="relative">
                                            <!-- Button -->
                                            <button onclick="toggleDropdownUser()" type="button"
                                                class="inputs flex justify-between text-base">
                                                Selecciona...
                                                <!-- Heroicon: chevron-down -->
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-chevron-down" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M6 9l6 6l6 -6" />
                                                </svg>
                                            </button>
                                            <!-- Panel -->
                                            <div id="dropdown-panel-users" style="display: none;"
                                                class="absolute right-0 top-4 z-10 w-full rounded-md bg-gray-100">
                                                @foreach ($allUsers as $user)
                                                    <label class="block px-4 py-2">
                                                        <input name="user_id[]" type="checkbox"
                                                            value="{{ $user->id }}" class="mr-2">
                                                        {{ $user->name }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="-mx-3 flex flex-row">
                                    <div class="flex w-full flex-col px-3">
                                        <h5 class="inline-flex font-semibold" for="project_id">
                                            Proyecto
                                        </h5>
                                        <select name="project_id" id="project_id" class="inputs">
                                            <option value="0" selected>Selecciona...</option>
                                            @foreach ($projects as $project)
                                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modalFooter">
                            <button type="submit" class="btnSave">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-device-floppy mr-2" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                    <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M14 4l0 4l-6 0l0 -4" />
                                </svg>
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- END MODAL EDIT / CREATE USER --}}
        @stack('js')
        @if (session('error'))
            <script>
                toastr['error']("{{ session('error') }}", "Error");
            </script>
        @endif
        @if (session('success'))
            <script>
                toastr['success']("{{ session('success') }}");
            </script>
        @endif
        <script src="{{ asset('js/twemoji.js') }}"></script>
        <script src="{{ asset('js/daterangepicker.js') }}"></script>
        <script>
            // MODAL CREAR/EDITAR
            $(document).ready(function() {
                $("#btn-new").on("click", function() {
                    $("#modal-edit-create").removeClass("hidden").addClass("show");
                });
                $("#btn-close-modal-create").on("click", function() {
                    $("#modal-edit-create").removeClass("show").addClass("hidden");
                });
            });
            let checkboxes = document.querySelectorAll('.priority-checkbox');
            // REVISION DE CHECKBOX
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    // Desmarcar todos los checkboxes
                    checkboxes.forEach(function(input) {
                        input.checked = false;
                    });
                    // Marcar el checkbox seleccionado
                    checkbox.checked = true;
                });
            });
            function toggleDropdownUser() {
                var panel = document.getElementById('dropdown-panel-users');
                if (panel.style.display === 'none') {
                    // Oculta todos los paneles de dropdown
                    var allPanels = document.querySelectorAll('[id^="dropdown-panel-users"]');
                    allPanels.forEach(function(panel) {
                        panel.style.display = 'none';
                    });
                    panel.style.display = 'block';
                } else {
                    panel.style.display = 'none';
                }
            }
            function toggleDropdownProject() {
                var panel = document.getElementById('dropdown-panel-projects');
                if (panel.style.display === 'none') {
                    // Oculta todos los paneles de dropdown
                    var allPanels = document.querySelectorAll('[id^="dropdown-panel-projects"]');
                    allPanels.forEach(function(panel) {
                        panel.style.display = 'none';
                    });
                    panel.style.display = 'block';
                } else {
                    panel.style.display = 'none';
                }
            }
        </script>
    </div>
@endsection