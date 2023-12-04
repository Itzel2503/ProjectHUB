<div class="-mx-3 md:flex mb-6">
    <div class="md:w-1/2 flex flex-col px-3 mb-6 md:mb-0">
        <h5 class="inline-flex font-semibold" for="name">
            Fecha seleccionada <p class="text-red">*</p>
        </h5>
        <div class="relative z-0 w-full group flex justify-between items-center">
            <label id="date" class="text-sm inline-flex font-semibold w-1/4"></label>
            <input wire:model='date' type="date" name="date" class="leading-snug border border-gray-400 block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
        </div>
        <div>
            <span class="text-red text-xs italic">
                @error('date')
                <span class="pl-2 text-red-500 text-xs italic">
                    {{$message}}
                </span>
                @enderror
            </span>
        </div>
    </div>
    <div class="md:w-1/2 flex flex-col px-3">
        <h5 class="inline-flex font-semibold" for="name">
            Motivo <p class="text-red">*</p>
        </h5>
        <input wire:model='reason' required type="text" name="reason" id="reason" class="leading-snug border border-gray-400 block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
        <div>
            <span class="text-red text-xs italic">
                @error('reason')
                <span class="pl-2 text-red-500 text-xs italic">
                    {{$message}}
                </span>
                @enderror
            </span>
        </div>
    </div>
</div>
<div class="-mx-3 md:flex mb-6">
    <div class="md:w-1/2 flex flex-col px-3 mb-6 md:mb-0">
        <h5 class="inline-flex font-semibold" for="name">
            Actividades compromiso a realizar en Home Office <p class="text-red">*</p>
        </h5>
        <textarea wire:model='activities' type="text" row="6" placeholder="" name="activities" id="activities" class="leading-snug border border-gray-400 block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto"></textarea> 
        <div>
            <span class="text-red text-xs italic">
                @error('activities')
                <span class="pl-2 text-red-500 text-xs italic">
                    {{$message}}
                </span>
                @enderror
            </span>
        </div>
    </div>
    <div class="md:w-1/2 flex flex-col px-3 mb-6 md:mb-0">
        <h5 class="inline-flex font-semibold" for="name">
            Personas a quien delega activiades durante su ausencia en caso de requerise alguna actidad en oficina <p class="text-red">*</p>
        </h5>
        <select wire:model='delegate_activities' required name="delegate_activities" id="delegate_activities" class="leading-snug border border-gray-400 block w-3/4 appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
            <option selected>Selecciona...</option>
            {{-- @foreach ($areas as $area) --}}
            {{-- <option value="{{ $area->id }}">{{ $area->name }}</option> --}}
            {{-- @endforeach --}}
        </select>
        <div>
            <span class="text-red text-xs italic">
                @error('delegate_activities')
                <span class="pl-2 text-red-500 text-xs italic">
                    {{$message}}
                </span>
                @enderror
            </span>
        </div>
    </div>
</div>