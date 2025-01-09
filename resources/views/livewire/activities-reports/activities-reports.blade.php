<div>
    <div class="px-4 py-4 sm:rounded-lg">
        {{-- PESTAÑAS --}}
        <nav class="flex flex-wrap md:flex-nowrap overflow-x-auto border-b-2 border-primaryColor">
            <button wire:click="setActiveTab('task')"
                class="border-primaryColor @if ($activeTab === 'task') rounded-t-lg border-x-2 border-t-2 @endif whitespace-nowrap px-3 py-2 md:py-4 text-xs md:text-sm font-medium">
                Mis tareas
            </button>
            <button wire:click="setActiveTab('created')"
                class="border-primaryColor @if ($activeTab === 'created') rounded-t-lg border-x-2 border-t-2 @endif whitespace-nowrap px-3 py-2 md:py-4 text-xs md:text-sm font-medium">
                Creadas por mí
            </button>
            <button wire:click="setActiveTab('actividades')"
                class="border-primaryColor @if ($activeTab === 'actividades') rounded-t-lg border-x-2 border-t-2 @endif whitespace-nowrap px-3 py-2 md:py-4 text-xs md:text-sm font-medium">
                Actividades
            </button>
            <button wire:click="setActiveTab('reportes')"
                class="border-primaryColor @if ($activeTab === 'reportes') rounded-t-lg border-x-2 border-t-2 @endif whitespace-nowrap px-3 py-2 md:py-4 text-xs md:text-sm font-medium">
                Reportes
            </button>
            @if (Auth::user()->area_id == 4)
                <button wire:click="setActiveTab('dukke')"
                    class="border-primaryColor @if ($activeTab === 'dukke') rounded-t-lg border-x-2 border-t-2 @endif whitespace-nowrap px-3 py-2 md:py-4 text-xs md:text-sm font-medium">
                    Dukke
                </button>
            @endif
        </nav>        
        {{-- END PESTAÑAS --}}
        {{-- TABLE --}}
        @if ($activeTab === 'task')
            <livewire:activities-reports.my-activities >
        @elseif ($activeTab === 'created')
            <livewire:activities-reports.created >
        @elseif ($activeTab === 'actividades')
            <livewire:projects.activities.table-activities :idsprint="null"  :project="null">
        @elseif ($activeTab === 'reportes')
            <livewire:projects.table-reports :project="null">
        @elseif ($activeTab === 'dukke')
            @if (Auth::user()->area_id == 4)
                <livewire:projects.table-reports :project="$dukke">
            @endif
        @endif
        {{-- END TABLE --}}
    </div>
    {{-- LOADING PAGE --}}
    <div class="absolute left-0 top-0 z-50 h-screen w-full" wire:loading
        wire:target="setActiveTab">
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
        
    </script>
@endpush
</div>
