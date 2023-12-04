<div  id="modal_create_edit" class="top-20 left-0 z-50 max-h-full overflow-y-auto hidden">        
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
            <div class="flex flex-col sm:flex-row px-6 py-2 bg-main-fund overflow-y-auto text-sm">
                <div class="w-full sm:w-3/5 md-3/4 mb-5 mt-5 flex flex-col">
                    <div class="-mx-3 md:flex mb-6">
                        <div class="md:w-1/2 flex flex-col px-3">
                            <h5 class="inline-flex font-semibold mb-3" for="name">
                                Tipo de permiso a solicitar:
                            </h5>
                            <select name="permits" id="permits" class="leading-snug border border-gray-400 block w-3/4 appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto" onchange="obtenerValorSeleccionado()">
                                <option selected value="">Selecciona...</option>
                                @foreach ($permits as $permit)
                                    <option value="{{ $permit->id }}">{{ $permit->name }}</option>
                                @endforeach
                            </select>                                
                        </div>
                    </div>

                    {{-- LIVEWIRE PERMITS --}}
                    <div id="permit1" style="display:none;">
                        @include('/activitycontrol/permits/modal/permit1')
                    </div>

                    <div id="permit2" style="display:none;">
                        <h1>Horas fuera de oficina</h1>
                    </div>

                    <div id="permit3" style="display:none;">
                        <h1>Permiso de ausencia</h1>
                    </div>

                    <div id="permit4" style="display:none;">
                        <h1>Vacaciones</h1>
                    </div>

                    <div id="permit5" style="display:none;">
                        <h1>Tiempo extra</h1>
                    </div>

                    <div class="flex justify-center items-center py-6 bg-main-fund">
                        <button class="px-4 py-2 text-white font-semibold text-white bg-secondary-fund hover:bg-secondary rounded cursor-pointer"> Guardar </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let closeModal = document.getElementById('close_modal');
    let modalCreateEdit = document.getElementById('modal_create_edit');

    closeModal.addEventListener('click', function() {
        modalCreateEdit.classList.add('hidden');
        modalCreateEdit.classList.remove('block');
    });
</script>

<script>
    function obtenerValorSeleccionado() {
        var selectPermit = document.getElementById('permits');
        var selectValue = parseInt(selectPermit.value, 10);

        switch (selectValue) {
            case 1:
                mostrarVista('permit1');
                break;
            case 2:
                mostrarVista('permit2');
                break;
            case 3:
                mostrarVista('permit3');
                break;
            case 4:
                mostrarVista('permit4');
                break;
            case 5:
                mostrarVista('permit5');
                break;
            default:
                console.error('Valor no reconocido:', selectValue);
                break;
        }
    }

    function mostrarVista(idVista) {
        var vistas = ['permit1', 'permit2', 'permit3', 'permit4', 'permit5'];

        vistas.forEach(function(vista) {
            document.getElementById(vista).style.display = 'none';
        });
        
        document.getElementById(idVista).style.display = 'block';
    }
</script>
