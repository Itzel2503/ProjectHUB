<div>
    <div class="flex flex-col px-5">
        @if (Auth::user()->type_user == 1 && Auth::user()->area_id == 1)
            @if (!$edit)
                <div class="mt-5 w-40">
                    <button wire:click='showEdit' class="btnSave">Editar</button>
                </div>
            @endif
        @endif
        @if ($edit)
            <div class="flex flex-row">
                {{-- PESTAÑAS --}}
                <nav class="flex flex-col md:flex-row">
                    @foreach ($sections as $section)
                        <div
                            class="border-primaryColor @if ($activeTab == $section->section) rounded-t-lg border-x-2 border-t-2 @else border-b-2 @endif flex whitespace-nowrap p-3">
                            <h6 wire:click="setActiveTab('{{ $section->section }}')"
                                class="mr-2 cursor-pointer text-base font-medium">{{ $section->section }}</h6>
                            <span wire:click="available('{{ $section->id }}')"
                                class="@if ($section->available == true) text-lime-600 @else text-red-600 @endif cursor-pointer font-semibold">
                                @if ($section->available == true)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                        <path
                                            d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-eye-off">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M10.585 10.587a2 2 0 0 0 2.829 2.828" />
                                        <path
                                            d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87" />
                                        <path d="M3 3l18 18" />
                                    </svg>
                                @endif
                            </span>
                        </div>
                    @endforeach
                </nav>
                <div class="border-primaryColor w-full border-b-2"></div>
                {{-- END PESTAÑAS --}}
            </div>
            <div class="mt-5 w-full justify-center lg:w-4/5">
                @if ($activeTab == 'Avisos')
                    <textarea wire:model.defer='avisos' cols="30" rows="10" class="textarea" placeholder="Notas de Avisos"></textarea>
                @elseif ($activeTab == 'Seguimiento')
                    <textarea wire:model.defer='seguimiento' cols="30" rows="10" class="textarea"
                        placeholder="Notas de Seguimiento"></textarea>
                @elseif ($activeTab == 'Pruebas piloto')
                    <textarea wire:model.defer='pruebas' cols="30" rows="10" class="textarea"
                        placeholder="Notas de Pruebas piloto"></textarea>
                @elseif ($activeTab == 'Resolución piloto')
                    <textarea wire:model.defer='resolucion' cols="30" rows="10" class="textarea"
                        placeholder="Notas de Resolución piloto"></textarea>
                @elseif ($activeTab == 'Entregado')
                    <textarea wire:model.defer='entregado' cols="30" rows="10" class="textarea" placeholder="Notas de Entregado"></textarea>
                @endif
                <div class="my-5 flex">
                    <button wire:click="saveComments" class="btnSave mr-5">Guardar</button>
                    <button wire:click="showEdit" class="btnSecondary">Cancelar</button>
                </div>
            </div>
        @else
            <div class="mb-5 flex flex-row">
                <nav class="mt-5 grid w-full grid-cols-1 gap-4 sm:grid-cols-1 lg:grid-cols-2 xl:grid-cols-4">
                    @foreach ($sections as $section)
                        @if ($section->available == true)
                            <div class="w-full">
                                <h6 class="text-secundaryColor mb-2 mr-2 text-center text-base font-medium">
                                    {{ $section->section }}</h6>
                                <textarea disabled class="textarea" rows="5" placeholder="Notas de Avisos">{{ $section->content }}</textarea>
                            </div>
                        @endif
                    @endforeach
                </nav>
            </div>
        @endif
    </div>
    {{-- LOADING PAGE --}}
    <div class="absolute left-0 top-0 z-50 h-screen w-full" wire:loading
        wire:target="showEdit, setActiveTab, available, saveComments">
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
            // AVISOS
            window.addEventListener('swal:modal', event => {
                toastr[event.detail.type](event.detail.text, event.detail.title);
            });
        </script>
    @endpush
</div>
