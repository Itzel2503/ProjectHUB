<div>
    <div class="px-4 py-4 sm:rounded-lg">
        <div class="flex flex-col justify-between gap-2 text-sm md:flex-row lg:text-base">
            @if ($sprints->isEmpty())
                @if (Auth::user()->type_user == 1 || Auth::user()->area_id == 1)
                    <!-- BTN NEW -->
                    <div class="flex w-40 flex-wrap md:inline-flex md:flex-nowrap">
                        <button wire:click="newSprint()" class="btnNuevo">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus mr-2"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Sprint
                        </button>
                    </div>
                @endif
            @else
                <div class="flex w-full flex-wrap md:inline-flex md:w-4/5 md:flex-nowrap">
                    {{-- NOMBRE --}}
                    <div class="mb-2 inline-flex h-12 w-2/6 bg-transparent px-2 md:mx-3 md:px-0">
                        @if (Auth::user()->type_user == 1 || Auth::user()->area_id == 1)
                            <div wire:poll.5s>
                                <span class="m-auto mr-5">
                                    <span class="inline-block font-semibold">Avance</span>
                                    <span class="{{ $percentageResolved == '100' ? 'text-lime-700' : ($percentageResolved >= 50 ? 'text-yellow-500' : 'text-red-600') }}">
                                        {{ $percentageResolved }}%
                                    </span>
                                </span>
                            </div>
                        @endif
                        <select wire:model="selectSprint" wire:change="selectSprint($event.target.value)"
                            class="inputs">
                            @foreach ($sprints as $sprint)
                                <option value="{{ $sprint->id }}">{{ $sprint->number }} - {{ $sprint->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if (Auth::user()->type_user == 1 || Auth::user()->area_id == 1)
                        {{-- ESTADO --}}
                        <div class="mb-2 inline-flex h-12 w-36 bg-transparent px-2 md:mx-3 md:px-0">
                            <select wire:change='updateStateSprint({{ $idSprint }}, $event.target.value)'
                                name="state" id="state" class="inputs">
                                <option selected value={{ $firstSprint->state }}>{{ $firstSprint->state }}</option>
                                @foreach ($filteredState as $action)
                                    <option value="{{ $action }}">{{ $action }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    {{-- FECHAS --}}
                    <div class="mx-2 w-auto">
                        <span class="inline-block font-semibold">Fecha de inicio:</span>
                        {{ \Carbon\Carbon::parse($startDate)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}<br>
                        <span class="inline-block font-semibold">Fecha de cierre:</span>
                        <span
                            class="">
                            {{ \Carbon\Carbon::parse($endDate)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                        </span>
                    </div>
                    @if (Auth::user()->type_user == 1 || Auth::user()->area_id == 1)
                        <div class="h-12 w-auto bg-transparent md:inline-flex">
                            <div class="flex justify-center">
                                <div id="dropdown-container" class="relative">
                                    <!-- Button -->
                                    <button id="toggle-button" type="button" class="flex items-center px-5 py-2.5">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-dots-vertical" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                            <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                            <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                        </svg>
                                    </button>
                                    <!-- Panel -->
                                    <div id="dropdown-panel"
                                        class="hidden absolute right-10 top-3 z-10 mt-2 w-32 rounded-md bg-gray-200">
                                        <!-- Botón Nuevo -->
                                        <div wire:click="newSprint()"
                                            class="flex cursor-pointer px-4 py-2 text-sm text-black">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-plus mr-2" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M12 5l0 14" />
                                                <path d="M5 12l14 0" />
                                            </svg>
                                            Nuevo
                                        </div>
                                        <!-- Botón Editar -->
                                        <div wire:click="editSprint({{ $idSprint }})"
                                            class="@if ($firstSprint->state == 'Cerrado') hidden @endif flex cursor-pointer px-4 py-2 text-sm text-black">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-edit mr-2" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                <path
                                                    d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                <path d="M16 5l3 3" />
                                            </svg>
                                            Editar
                                        </div>
                                        <!-- Botón Eliminar -->
                                        <div wire:click="$emit('deleteSprint',{{ $idSprint }})"
                                            class="@if ($firstSprint->state == 'Cerrado' || $firstSprint->state == 'Curso') hidden @endif flex cursor-pointer px-4 py-2 text-sm text-red-600">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-trash mr-2" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M4 7l16 0" />
                                                <path d="M10 11l0 6" />
                                                <path d="M14 11l0 6" />
                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                            </svg>
                                            Eliminar
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
            <div class="mb-2 h-12 w-1/6 bg-transparent md:inline-flex">
                <button wire:click="showBacklog()" class="btnNuevo">
                    Backlog
                </button>
            </div>
        </div>
    </div>
    {{-- MODAL SHOW BACKLOG --}}
    @if ($showBacklog)
        <div class="block left-0 top-20 z-50 max-h-full overflow-y-auto">
            <div
                class="fixed left-0 top-0 z-30 flex h-full w-full items-center justify-center bg-gray-500 bg-cover bg-no-repeat opacity-80">
            </div>
            <div class="text:md smd:px-0 fixed left-0 top-0 z-40 flex h-full w-full items-center justify-center px-2">
                <div class="mx-auto flex flex-col overflow-y-auto rounded-lg md:w-3/4" style="max-height: 90%;">
                    <div
                        class="flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-6 py-4 text-white">
                        <h3
                            class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                            Backlog</h3>
                        <svg wire:click="showBacklog()" class="h-6 w-6 cursor-pointer text-black hover:stroke-2"
                            xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M18 6l-12 12"></path>
                            <path d="M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div class="modalBody">
                        <div
                            class="md-3/4 mb-5 flex w-full flex-col border-gray-400 px-5 md:mb-0 lg:w-1/2 lg:border-r-2">
                            <div class="-mx-3 mb-6 flex flex-row">
                                <div class="mb-6 flex w-full flex-col px-3">
                                    <h5 class="text-text2 text-lg font-bold">
                                        Fecha de inicio
                                    </h5>
                                    <p>
                                        {{ \Carbon\Carbon::parse($backlog->start_date)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                                    </p>
                                </div>
                                <div class="flex w-full flex-col px-3">
                                    <h5 class="text-text2 text-lg font-bold">
                                        Fecha de cierre
                                    </h5>
                                    <p>
                                        {{ \Carbon\Carbon::parse($backlog->closing_date)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                                    </p>
                                </div>
                            </div>
                            <div class="-mx-3 mb-6">
                                <div class="mb-6 flex w-full flex-col px-3">
                                    <h5 class="text-text2 text-lg font-bold">
                                        Objetivo generales
                                    </h5>
                                    <p>{{ $backlog->general_objective }}</p>
                                </div>
                            </div>
                            <div class="-mx-3 mb-6">
                                <div class="mb-6 flex w-full flex-col px-3">
                                    <h5 class="text-text2 text-lg font-bold">
                                        Claves de acceso
                                    </h5>
                                    <textarea required disabled type="text" rows="10" name="scopes" id="scopes" class="textarea">{{ $backlog->passwords }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="w-full px-5 lg:w-1/2">
                            <div class="-mx-3 mb-6">
                                <div class="mb-6 flex w-full flex-col px-3">
                                    <h5 class="text-text2 text-lg font-bold">
                                        Alcances
                                    </h5>
                                    @if ($backlog->scopes)
                                        <textarea required disabled type="text" rows="10" name="scopes" id="scopes" class="textarea">{{ $backlog->scopes }}</textarea>
                                    @endif
                                    @if ($backlog->filesBacklog)
                                        @foreach ($backlog->filesBacklog as $file)
                                            @if ($file)
                                                <a href="{{ asset('backlogs/' . $file) }}" target="_blank"
                                                    class="my-3">
                                                    <img src="{{ asset('backlogs/' . $file) }}" alt="Backlog Image">
                                                </a>
                                            @else
                                                <div
                                                    class="md-3/4 mb-5 mt-3 flex w-full flex-col items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-photo-off">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M15 8h.01" />
                                                        <path
                                                            d="M7 3h11a3 3 0 0 1 3 3v11m-.856 3.099a2.991 2.991 0 0 1 -2.144 .901h-12a3 3 0 0 1 -3 -3v-12c0 -.845 .349 -1.608 .91 -2.153" />
                                                        <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5" />
                                                        <path d="M16.33 12.338c.574 -.054 1.155 .166 1.67 .662l3 3" />
                                                        <path d="M3 3l18 18" />
                                                    </svg>
                                                    <p>Sin imagen</p>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- END MODAL SHOW BACKLOG --}}
    {{-- MODAL EDIT / CREATE SPRINT --}}
    @if ($newSprint)
        <div class="left-0 top-20 z-50 max-h-full overflow-y-auto">
            <div
                class="fixed left-0 top-0 z-30 flex h-screen w-full items-center justify-center bg-gray-500 bg-cover bg-no-repeat opacity-80">
            </div>
            <div class="text:md fixed left-0 top-0 z-40 flex h-screen w-full items-center justify-center">
                <div class="mx-auto flex flex-col overflow-y-auto rounded-lg md:w-2/5" style="max-height: 90%;">
                    <div
                        class="flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-6 py-4 text-white">
                        <h3
                            class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                            @if ($updateSprint)
                                Editar sprint {{ $sprintEdit->number }}
                            @else
                                Crear sprint
                            @endif
                        </h3>
                        <svg wire:click="newSprint()" class="my-2 h-6 w-6 cursor-pointer text-black hover:stroke-2"
                            xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M18 6l-12 12"></path>
                            <path d="M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div class="modalBody">
                        <div class="md-3/4 mb-5 mt-5 flex w-full flex-col">
                            <div class="-mx-3 mb-6">
                                <div class="mb-6 flex w-full flex-col px-3">
                                    <h5 class="inline-flex font-semibold" for="name_sprint">
                                        Nombre<p class="text-red-600">*</p>
                                    </h5>
                                    <input wire:model='name_sprint' required type="text" placeholder="Nombre"
                                        name="name_sprint" id="name_sprint" class="inputs">
                                    <div>
                                        <span class="text-xs italic text-red-600">
                                            @error('name_sprint')
                                                <span class="pl-2 text-xs italic text-red-600">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="-mx-3 flex flex-row">
                                <div class="mb-6 flex w-full flex-col px-3">
                                    <h5 class="inline-flex font-semibold" for="start_date">
                                        Fecha de inicio<p class="text-red-600">*</p>
                                    </h5>
                                    <input wire:model='start_date' required type="date" name="start_date"
                                        id="start_date" class="inputs">
                                    <div>
                                        <span class="text-xs italic text-red-600">
                                            @error('start_date')
                                                <span class="pl-2 text-xs italic text-red-600">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="flex w-full flex-col px-3">
                                    <h5 class="inline-flex font-semibold" for="end_date">
                                        Fecha de cierre<p class="text-red-600">*</p>
                                    </h5>
                                    <input wire:model='end_date' required type="date" name="end_date"
                                        id="end_date" class="inputs">
                                    <div>
                                        <span class="text-xs italic text-red-600">
                                            @error('end_date')
                                                <span class="pl-2 text-xs italic text-red-600">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modalFooter">
                        @if ($updateSprint)
                            <button class="btnSave" wire:click="updateSprint({{ $sprintEdit->id }})"> Guardar
                            </button>
                        @else
                            <button class="btnSave" wire:click="createSprint()">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-device-floppy mr-2" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                    <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M14 4l0 4l-6 0l0 -4" />
                                </svg>
                                Guardar
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- END MODAL EDIT / CREATE SPRINT --}}
    <div class="absolute left-0 top-0 z-50 h-screen w-full" wire:loading
        wire:target="newSprint, editSprint, deleteSprint, showBacklog, updateSprint, createSprint">
        <div class="absolute z-10 h-screen w-full bg-gray-200 opacity-40"></div>
        <div class="loadingspinner relative top-1/3 z-20">
            <div id="square1"></div>
            <div id="square2"></div>
            <div id="square3"></div>
            <div id="square4"></div>
            <div id="square5"></div>
        </div>
    </div>
    @if (!$sprints->isEmpty())
        <livewire:projects.activities.table-activities :idsprint="$idSprint"  :project="$project"  wire:key="table-activities-{{ $idSprint }}">
    @endif
    @push('js')
        <script>
            const toggleButton = document.getElementById('toggle-button');
            const dropdownPanel = document.getElementById('dropdown-panel');

            // Alternar la visibilidad del panel al hacer clic en el botón
            toggleButton.addEventListener('click', () => {
                const isHidden = dropdownPanel.classList.contains('hidden');
                dropdownPanel.classList.toggle('hidden', !isHidden);
            });

            // Ocultar el panel si se hace clic fuera de él
            document.addEventListener('click', (event) => {
                const isClickInside = toggleButton.contains(event.target) || dropdownPanel.contains(event.target);
                if (!isClickInside) {
                    dropdownPanel.classList.add('hidden');
                }
            });
            // MODAL
            window.addEventListener('swal:modal', event => {
                toastr[event.detail.type](event.detail.text, event.detail.title);
            });

            Livewire.on('deleteSprint', deletebyId => {
                Swal.fire({
                    title: '¿Seguro que deseas eliminar este elemento?',
                    text: "Esta acción es irreversible",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#202a33',
                    cancelButtonColor: '#ef4444',
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.livewire.emit('destroySprint', deletebyId);
                        Swal.fire(
                            '¡Eliminado!',
                            'Sprint eliminado',
                            'Exito'
                        )
                    }
                })
            });
        </script>
    @endpush
</div>
