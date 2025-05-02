<div>
    <div class="px-4 py-4 sm:rounded-lg">
        <div class="flex flex-col justify-between gap-2 text-sm md:flex-row lg:text-base">
            @if ($sprints->isEmpty())
                <div class=" flex flex-col items-center w-full text-center mt-5 text-primaryColor-light">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-book-off">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 5.899 -1.096" />
                        <path d="M3 6a9 9 0 0 1 2.114 -.884m3.8 -.21c1.07 .17 2.116 .534 3.086 1.094a9 9 0 0 1 9 0" />
                        <path d="M3 6v13" />
                        <path d="M12 6v2m0 4v7" />
                        <path d="M21 6v11" />
                        <path d="M3 3l18 18" />
                    </svg>
                    <p class="text-xl font-bold">No existen desarrollos</p>
                </div>
            @else
                <div class="flex w-full flex-wrap md:inline-flex md:w-4/5 md:flex-nowrap">
                    {{-- AVANCE ESTATUS --}}
                    <div class="mx-2 w-auto">
                        <span class="inline-block font-semibold">Avance:</span>
                        <span
                            class="{{ $percentageResolved == '100' ? 'text-lime-700' : ($percentageResolved >= 50 ? 'text-yellow-500' : 'text-red-600') }}">
                            {{ $percentageResolved }}%
                        </span>
                        <br>
                        <span class="inline-block font-semibold">Estatus:</span>
                        <span>{{ $firstSprint->state }}</span>
                    </div>
                    {{-- FECHAS --}}
                    <div class="md:hidden mx-2 w-auto">
                        <span class="inline-block font-semibold">Fecha de inicio:</span>
                        {{ \Carbon\Carbon::parse($startDate)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}<br>
                        <span class="inline-block font-semibold">Fecha de cierre:</span>
                        <span class="">
                            {{ \Carbon\Carbon::parse($endDate)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                        </span>
                    </div>
                    {{-- NOMBRE --}}
                    <div class="mb-2 inline-flex h-12 w-full md:w-2/6 bg-transparent px-2 md:mx-3 md:px-0">
                        <select wire:model.defer="selectSprint" wire:change="selectSprint($event.target.value)"
                            class="inputs">
                            @foreach ($sprints as $sprint)
                                <option value="{{ $sprint->id }}">{{ $sprint->number }} - {{ $sprint->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- FECHAS --}}
                    <div class="mx-2 w-auto hidden md:block">
                        <span class="inline-block font-semibold">Fecha de inicio:</span>
                        {{ \Carbon\Carbon::parse($startDate)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}<br>
                        <span class="inline-block font-semibold">Fecha de cierre:</span>
                        <span class="">
                            {{ \Carbon\Carbon::parse($endDate)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                        </span>
                    </div>
                </div>
                <div class="md:hidden">
                    <button wire:click="showBacklog()" class="btnSave">
                        Backlog
                    </button>
                </div>
            @endif
            <div class="hidden md:block mb-2 h-12 md:w-1/6 bg-transparent md:inline-flex">
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
                <div class="mx-auto flex flex-col overflow-y-auto rounded-lg w-full md:w-3/4" style="max-height: 90%;">
                    <div
                        class="flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-6 py-4 text-white">
                        <h3
                            class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                            Backlog</h3>
                        <svg wire:click="showBacklog()" class="h-6 w-6 cursor-pointer text-black hover:stroke-2"
                            xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
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
        <livewire:projects.activities.table-activities-client :idsprint="$idSprint" :project="$project"
            wire:key="table-activities-{{ $idSprint }}">
    @endif
    @push('js')
        <script>
            // MODAL
            window.addEventListener('swal:modal', event => {
                toastr[event.detail.type](event.detail.text, event.detail.title);
            });
        </script>
    @endpush
</div>
