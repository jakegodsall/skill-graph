<div class="text-gray-700">
    <div class="flex gap-4">
        <div>
            <label class="font-medium"> {{ $labelA }}</label>
            <input
                type="text"
                class="form-control border py-2 px-4 rounded-md w-full"
                wire:model.debounce.500ms="valueA"
                inputmode="numeric"
                pattern="[0-9]*"
                @if($disabled) disabled @endif
            >
        </div>
        <div>
            <label class="font-medium">{{ $labelB }}</label>
            <input
                type="text"
                wire:model.debounce.500ms="valueB"
                readonly
                class="form-control border px-4 py-2 rounded-md w-full"
                @if($disabled) disabled @endif
            >
        </div>
    </div>

    <div class="mt-4 relative">
        <div class="flex items-center gap-4 mb-2">
            <label class="font-medium block">Adjust Split:</label>
            <div id="sliderValue" class="bg-main-50 text-white text-xs px-2 py-1 rounded-md">
                {{ number_format($percentage, 0) }}%
            </div>
        </div>
        <div class="relative w-full">
            <input
                type="range"
                wire:model="percentage"
                min="0"
                max="100"
                class="w-full h-2 accent-main-50 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                id="slider"
                @if($disabled) disabled @endif
            >
        </div>
        <div class="flex justify-between text-sm text-gray-600 mt-2">
            <span>0%</span>
            <span>100%</span>
        </div>
        <div class="text-center text-lg font-semibold mt-2">
            <span class="text-main-50">£{{ number_format($valueA, 2) }}</span> / 
            <span class="text-gray-700">£{{ number_format($valueB, 2) }}</span>
        </div>
    </div>
</div>