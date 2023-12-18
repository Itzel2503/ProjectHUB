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

<script>
    $selectOther = document.getElementById('take_hours');
    $inputOther = document.getElementById('other_take_hours');

    $selectOther.addEventListener('change', function() {
        if ($selectOther.value == 'other') {
            $inputOther.classList.remove('hidden');
            $inputOther.classList.add('block');
        } else {
            $inputOther.classList.add('hidden');
            $inputOther.classList.remove('block');
        }
    });
</script>