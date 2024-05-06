<div>
    {{-- Tabla actividades --}}
    <div class="px-4 py-4 sm:rounded-lg">
        {{-- NAVEGADOR --}}
        <div class="flex flex-col justify-between gap-2 text-sm md:flex-row lg:text-base">
            <!-- SEARCH -->
            <div class="flex w-full flex-wrap md:inline-flex md:w-4/5 md:flex-nowrap">
                <div class="mb-2 inline-flex h-12 w-1/2 bg-transparent px-2 md:w-2/5 md:px-0">
                    <div class="relative flex h-full w-full">
                        <div class="absolute z-10 mt-2 flex">
                            <span
                                class="whitespace-no-wrap flex items-center rounded-lg border-0 border-none bg-transparent p-2 leading-normal lg:px-3">
                                <svg width="18" height="18" class="w-4 lg:w-auto" viewBox="0 0 18 18" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M8.11086 15.2217C12.0381 15.2217 15.2217 12.0381 15.2217 8.11086C15.2217 4.18364 12.0381 1 8.11086 1C4.18364 1 1 4.18364 1 8.11086C1 12.0381 4.18364 15.2217 8.11086 15.2217Z"
                                        stroke="#455A64" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M16.9993 16.9993L13.1328 13.1328" stroke="#455A64" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </span>
                        </div>
                        <input wire:model="search" type="text" placeholder="Buscar" class="inputs"
                            style="padding-left: 3em;">
                    </div>
                </div>
            </div>
        </div>
        {{-- END NAVEGADOR --}}
        {{-- TABLE --}}
        <div class="tableStyle">
            <table class="whitespace-no-wrap table-hover table w-full">
                <thead class="headTable border-0">
                    <tr>
                        <th class="w-96 px-4 py-3">Actividad</th>
                        <th class="px-4 py-3 lg:w-48">
                            <div @if ($progressFiltered) wire:click="orderByLowDates('progress')" @else
                                wire:click="orderByHighDates('progress')" @endif
                                class="inline-flex justify-center">
                                Delegado
                                @if ($progressFiltered)
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-sort-descending ml-2" width="24"
                                        height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 6l9 0" />
                                        <path d="M4 12l7 0" />
                                        <path d="M4 18l7 0" />
                                        <path d="M15 15l3 3l3 -3" />
                                        <path d="M18 6l0 12" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-sort-ascending ml-2 cursor-pointer"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 6l7 0" />
                                        <path d="M4 12l7 0" />
                                        <path d="M4 18l9 0" />
                                        <path d="M15 9l3 -3l3 3" />
                                        <path d="M18 6l0 12" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">
                            <div @if ($expectedFiltered) wire:click="orderByLowDates('expected_date')" @else
                                wire:click="orderByHighDates('expected_date')" @endif
                                class="inline-flex justify-center">
                                Fecha
                                @if ($expectedFiltered)
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-sort-descending ml-2" width="24"
                                        height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 6l9 0" />
                                        <path d="M4 12l7 0" />
                                        <path d="M4 18l7 0" />
                                        <path d="M15 15l3 3l3 -3" />
                                        <path d="M18 6l0 12" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-sort-ascending ml-2 cursor-pointer"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 6l7 0" />
                                        <path d="M4 12l7 0" />
                                        <path d="M4 18l9 0" />
                                        <path d="M15 9l3 -3l3 3" />
                                        <path d="M18 6l0 12" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-3">
                            <div @if ($createdFiltered) wire:click="orderByLowDates('created_at')" @else
                                wire:click="orderByHighDates('created_at')" @endif
                                class="inline-flex justify-center">
                                Creado
                                @if ($createdFiltered)
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-sort-descending ml-2" width="24"
                                        height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 6l9 0" />
                                        <path d="M4 12l7 0" />
                                        <path d="M4 18l7 0" />
                                        <path d="M15 15l3 3l3 -3" />
                                        <path d="M18 6l0 12" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-sort-ascending ml-2 cursor-pointer"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 6l7 0" />
                                        <path d="M4 12l7 0" />
                                        <path d="M4 18l9 0" />
                                        <path d="M15 9l3 -3l3 3" />
                                        <path d="M18 6l0 12" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="w-2 px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activities as $activity)
                        <tr class="trTable">
                            <td class="relative px-4 py-2">
                                <div wire:click="showActivity({{ $activity->id }})"
                                    class="flex cursor-pointer flex-col items-center justify-center text-center">
                                    @if ($activity->sprint && $activity->sprint->backlog && $activity->sprint->backlog->project)
                                        <p class="text-justify text-xs font-semibold">
                                            Proyecto: {{ $activity->sprint->backlog->project->name }}
                                        </p>
                                    @else
                                        <p class="text-justify text-xs font-semibold">
                                            Proyecto no disponible
                                        </p>
                                    @endif
                                    <p class="mb-2 text-justify text-xs font-semibold">{{ $activity->tittle }}</p>
                                    @if (!empty($activity->image))
                                        <div class="h-22 relative w-auto px-3 pt-2">
                                            @if ($activity->image == true)
                                                <img src="{{ asset('activities/' . $activity->image) }}"
                                                    alt="Activity Image" class="mx-auto h-16 w-20 object-cover">
                                            @endif
                                        </div>
                                    @endif
                                    @if ($activity->messages_count >= 1 && $activity->user_chat != Auth::id())
                                        <div class="absolute right-0 top-0 mr-2 mt-2">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-circle-filled text-red-600"
                                                width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z"
                                                    stroke-width="0" fill="currentColor" />
                                            </svg>
                                        </div>
                                    @endif
                                    @if ($activity->priority)
                                        <div
                                            class="@if ($activity->priority == 'Alto') bg-red-500 text-white @endif @if ($activity->priority == 'Medio') bg-yellow-400 @endif @if ($activity->priority == 'Bajo') bg-blue-500 text-white @endif my-2 w-auto rounded-md px-3 text-center font-semibold">
                                            {{ $activity->priority }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-2">
                                <div class="mx-auto w-auto text-left">
                                    <p class="font-semibold">
                                        {{ $activity->delegate->name }} {{ $activity->delegate->lastname }}</p>
                                    <p class="text-xs">
                                        @if ($activity->state == 'Proceso' || $activity->state == 'Conflicto')
                                            Progreso {{ $activity->progress->diffForHumans(null, false, false, 1) }}
                                        @else
                                            @if ($activity->state == 'Resuelto')
                                                @if ($activity->progress == null)
                                                    Sin tiempo de desarrollo
                                                @else
                                                    Tiempo de desarrollo {{ $activity->timeDifference }}
                                                @endif
                                            @else
                                                @if ($activity->look == true)
                                                    Visto
                                                    {{ $activity->progress->diffForHumans(null, false, false, 1) }}
                                                @endif
                                            @endif
                                        @endif
                                    </p>
                                </div>
                            </td>
                            <td class="px-4 py-2">
                                <div wire:change='updateState({{ $activity->id }}, $event.target.value)'
                                    name="state" id="state"
                                    class="inpSelectTable inpSelectTable @if ($activity->state == 'Abierto') bg-blue-500 text-white @endif @if ($activity->state == 'Proceso') bg-yellow-400 @endif @if ($activity->state == 'Resuelto') bg-lime-700 text-white @endif @if ($activity->state == 'Conflicto') bg-red-600 text-white @endif w-28 text-sm font-semibold">
                                    <option selected value={{ $activity->state }}>{{ $activity->state }}</option>
                                </div>
                            </td>
                            <td class="px-4 py-2 text-left">
                                <div class="mx-auto">
                                    <span class="inline-block font-semibold">Entrega:</span>
                                    {{ \Carbon\Carbon::parse($activity->expected_date)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}<br>
                                    <span class="inline-block font-semibold">Delegado el:</span>
                                    {{ \Carbon\Carbon::parse($activity->delegated_date)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                                </div>
                            </td>
                            <td class="px-4 py-2">
                                <div class="mx-auto text-left">
                                    <span class="font-semibold"> {{ $activity->user->name }}
                                        {{ $activity->user->lastname }} </span> <br>
                                    <span class="font-mono">
                                        {{ \Carbon\Carbon::parse($activity->created_at)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                                    </span><br>
                                    <span class="italic">
                                        {{ $activity->created_at->diffForHumans(null, false, false, 1) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-5">
                                <div class="flex justify-center">
                                    @if ($activity->sprint && $activity->sprint->backlog && $activity->sprint->backlog->project)
                                        <a
                                            href="{{ route('projects.activities.index', ['project' => $activity->sprint->backlog->project->id, 'activity' => $activity->id]) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                <path
                                                    d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                            </svg>
                                        </a>
                                    @else
                                        <p class="text-justify text-xs font-semibold">
                                            Proyecto no disponible
                                        </p>
                                    @endif
                                    
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- END TABLE --}}
    {{-- MODAL SHOW ACTIVITY --}}
    <div
        class="@if ($modalShowActivity) block  @else hidden @endif left-0 top-20 z-50 max-h-full overflow-y-auto">
        <div
            class="fixed left-0 top-0 z-30 flex h-full w-full items-center justify-center bg-gray-500 bg-cover bg-no-repeat opacity-80">
        </div>
        <div class="text:md smd:px-0 fixed left-0 top-0 z-40 flex h-full w-full items-center justify-center px-2">
            <div class="mx-auto flex flex-col overflow-y-auto rounded-lg md:w-3/4" style="max-height: 90%;">
                @if (!empty($activityShow->image))
                    <div
                        class="flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-6 py-4 text-white">
                        <h3
                            class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                            @php
                                echo mb_substr($activityShow->title, 0, 25) . ' ...';
                            @endphp
                        </h3>
                        <svg wire:click="modalShowActivity()" class="h-6 w-6 cursor-pointer text-black hover:stroke-2"
                            xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M18 6l-12 12"></path>
                            <path d="M6 6l12 12"></path>
                        </svg>
                    </div>
                @else
                    <div
                        class="bg-main-fund flex flex-row justify-end rounded-tl-lg rounded-tr-lg px-6 py-4 text-white">
                        <svg wire:click="modalShowActivity()" class="h-6 w-6 cursor-pointer text-black hover:stroke-2"
                            xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M18 6l-12 12"></path>
                            <path d="M6 6l12 12"></path>
                        </svg>
                    </div>
                @endif
                @if ($showActivity)
                    <div class="flex flex-col items-stretch overflow-y-auto bg-white px-6 py-2 text-sm lg:flex-row">
                        <div
                            class="md-3/4 mb-5 mt-3 flex w-full flex-col justify-between border-gray-400 px-5 md:mb-0 lg:w-1/2 lg:border-r-2">
                            <div class="text-justify text-base">
                                <h3 class="text-text2 text-lg font-bold">Descripción</h3>
                                {!! nl2br(e($activityShow->description)) !!}<br><br>
                                @if ($showChat)
                                    <h3 class="text-text2 text-base font-semibold">Comentarios</h3>
                                    <div
                                        class="border-primaryColor max-h-80 overflow-y-scroll rounded-br-lg border-4 px-2 py-2">
                                        @foreach ($messages as $message)
                                            <div class="inline-flex">
                                                @if ($message->messages_count >= 1 && $activityShow->user_chat != Auth::id() && $message->look == false)
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-exclamation-mark text-red-600"
                                                        width="24" height="24" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor" fill="none"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M12 19v.01" />
                                                        <path d="M12 15v-10" />
                                                    </svg>
                                                @endif
                                                <p class="pr-1 text-sm text-black">
                                                    <span class="font-semibold"> {{ $message->transmitter->name }}:
                                                    </span>
                                                    <span
                                                        class="text-sm font-extralight text-gray-600">{{ $message->message }}</span>
                                                </p>
                                            </div>
                                            <br>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="my-6 flex w-auto flex-row">
                                <input wire:model.defer='message' type="text" name="message" id="message"
                                    placeholder="Mensaje a los administradores" class="inputs"
                                    style="border-radius: 0.5rem 0px 0px 0.5rem !important">
                                <button class="btnSave" style="border-radius: 0rem 0.5rem 0.5rem 0rem !important"
                                    wire:click="updateChat({{ $activityShow->id }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-send"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M10 14l11 -11" />
                                        <path
                                            d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" />
                                    </svg>
                                    Comentar
                                </button>
                            </div>
                        </div>
                        <div class="photos w-full px-5 lg:w-1/2">
                            @if (!empty($activityShow->image))
                                <div id="example" class="mb-6 w-auto">
                                    <div class="md-3/4 mb-5 mt-5 flex w-full flex-row justify-between">
                                        <div class="text-text2 text-center text-xl font-semibold md:flex">
                                            Detalle
                                        </div>
                                    </div>
                                    @if ($activityShow->imageExists)
                                        <div class="md-3/4 mb-5 mt-3 flex w-full flex-col">
                                            <img src="{{ asset('activities/' . $activityShow->image) }}"
                                                alt="Activity Image">
                                        </div>
                                    @else
                                        <div class="md-3/4 mb-5 mt-3 flex w-full flex-col items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                                @else
                                    <div class="my-5 w-full text-center text-lg">
                                        <p class="text-red my-5">Sin archivo</p>
                                    </div>
                                </div>
                            @endif
                            @if ($activityShow->image == true)
                                <div class="flex items-center justify-center">
                                    <a href="{{ asset('activities/' . $activityShow->image) }}"
                                        download="{{ basename($activityShow->image) }}" class="btnSecondary"
                                        style="color: white;">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-download" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                            <path d="M7 11l5 5l5 -5" />
                                            <path d="M12 4l0 12" />
                                        </svg>
                                        &nbsp;
                                        Descargar
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    {{-- END MODAL SHOW ACTIVITY --}}
    {{-- LOADING PAGE --}}
    <div class="absolute left-0 top-0 z-50 h-screen w-full" wire:loading wire:target="">
        <div class="absolute z-10 h-screen w-full bg-gray-200 opacity-40"></div>
        <div class="loadingspinner relative top-1/3 z-20">
            <div id="square1"></div>
            <div id="square2"></div>
            <div id="square3"></div>
            <div id="square4"></div>
            <div id="square5"></div>
        </div>
    </div>
    {{-- END LOADING PAGE --}}
    @push('js')
        <script>
            // MODALS
            window.addEventListener('swal:modal', event => {
                toastr[event.detail.type](event.detail.text, event.detail.title);
            });
        </script>
    @endpush
</div>
