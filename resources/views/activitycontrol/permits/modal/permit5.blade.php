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