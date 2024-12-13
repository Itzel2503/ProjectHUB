<div>
    @if (Auth::user()->type_user == 3)
        <p class="my-auto text-left text-xs font-semibold">Arten</p>
    @else
        @if ($report->delegate)
            <p class="@if ($report->state == 'Resuelto') font-semibold @else hidden @endif">
                {{ $report->delegate->name }}
            </p>
            <select name="delegate" id="delegate"
                class="inpSelectTable @if ($report->state == 'Resuelto') hidden @endif w-full text-sm">
                <option selected value={{ $report->delegate->id }}>
                    {{ $report->delegate->name }} </option>
            </select>
        @else
            <p class="@if ($report->state == 'Resuelto') font-semibold @else hidden @endif">
                Usuario eliminado
            </p>
            <select name="delegate" id="delegate"
                class="inpSelectTable @if ($report->state == 'Resuelto') hidden @endif w-full text-sm">
                <option selected>
                    Seleccionar...</option>
            </select>
        @endif
    @endif
</div>
