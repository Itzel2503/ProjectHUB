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
            <form action="permits" method="post">
            @csrf
            {{-- INPUT DE FECHA SELECCIONADA --}}
            <input hidden type="text" name="date" id="date_input">

                <div class="flex flex-col sm:flex-row px-6 py-2 bg-main-fund overflow-y-auto text-sm">
                    <div class="w-3/5 sm:w-full md-3/4 mb-5 mt-5 flex flex-col">
                        <div id="permit0" class="-mx-3 md:flex mb-6">
                            <div class="md:w-1/2 flex flex-col px-3">
                                <h5 class="inline-flex font-semibold mb-3" for="name">
                                    Tipo de permiso a solicitar:
                                </h5>
                                <select name="type_permits" id="type_permits" class="leading-snug border border-gray-400 block w-3/4 appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto" onchange="obtenerValorSeleccionado()">
                                    <option selected value="0">Selecciona...</option>
                                    @foreach ($permits as $permit)
                                        <option value="{{ $permit->id }}">{{ $permit->name }}</option>
                                    @endforeach
                                </select>                                
                            </div>
                        </div>

                        {{-- PERMITS --}}
                        <div id="permit1" style="display:none;">
                            @include('/activitycontrol/permits/modal/permit1')
                        </div>

                        <div id="permit2" style="display:none;">
                            @include('/activitycontrol/permits/modal/permit2')
                        </div>

                        <div id="permit3" style="display:none;">
                            @include('/activitycontrol/permits/modal/permit3')
                        </div>

                        <div id="permit4" style="display:none;">
                            @include('/activitycontrol/permits/modal/permit4')
                        </div>

                        <div id="permit5" style="display:none;">
                            @include('/activitycontrol/permits/modal/permit5')
                        </div>

                        <div class="flex justify-center items-center py-6 bg-main-fund">
                            <button id="btn_save" class="hidden px-4 py-2 text-white font-semibold bg-secondary-fund hover:bg-secondary rounded cursor-pointer"> Guardar </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let closeModal = document.getElementById('close_modal');
    let modalCreateEdit = document.getElementById('modal_create_edit');
    let btnSave = document.getElementById('btn_save');

    closeModal.addEventListener('click', function() {
        modalCreateEdit.classList.add('hidden');
        modalCreateEdit.classList.remove('block');
        
    });
</script>

<script>
    function resetAllFields() {
        // Restablecer todos los campos de entrada, excepto el select con id "type_permits"
        var inputs = document.querySelectorAll('input:not(#type_permits), select:not(#type_permits)');
        inputs.forEach(function(input) {
            input.value = '';
        });
    }

    function setRequiredFields(suffix) {
        // Agregar el atributo 'required' a los campos específicos según el sufijo proporcionado
        var requiredFields = document.querySelectorAll('.required-' + suffix);
        requiredFields.forEach(function(field) {
            field.setAttribute('required', true);
        });
    }


    function obtenerValorSeleccionado() {
        var selectPermit = document.getElementById('type_permits');
        var selectValue = parseInt(selectPermit.value, 10);

        resetAllFields(); // Restablecer todos los campos antes de asignar valores específicos

        switch (selectValue) {
            case 0:
                mostrarVista('permit0');
                btnSave.classList.add('hidden');
                break;
            case 1:
                mostrarVista('permit1');
                btnSave.classList.remove('hidden');
                setRequiredFields('fiels1');
                break;
            case 2:
                mostrarVista('permit2');
                btnSave.classList.remove('hidden');
                setRequiredFields('fiels2');
                break;
            case 3:
                mostrarVista('permit3');
                btnSave.classList.remove('hidden');
                setRequiredFields('fiels3');
                break;
            case 4:
                mostrarVista('permit4');
                btnSave.classList.remove('hidden');
                setRequiredFields('fiels4');
                break;
            case 5:
                mostrarVista('permit5');
                btnSave.classList.remove('hidden');
                setRequiredFields('fiels5');
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

<script>
    function validateNumberInput(input) {
        input.value = input.value.replace(/[^0-9.]/g, '');

        // If there are more than one decimal points, remove the extra ones
        let decimalCount = (input.value.match(/\./g) || []).length;
        if (decimalCount > 1) {
            input.value = input.value.replace(/\.+$/, '');
        }
    }
</script>
