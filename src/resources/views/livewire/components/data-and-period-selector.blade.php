<div class="flex gap-2 items-center">
    <!-- Date Navigation Buttons -->
    <div class="flex space-x-2 items-center">
        <button wire:click="navigateDate('previous')" class="btn btn-secondary">
            &larr;
        </button>
        <span class="text-md select-none cursor-pointer" wire:click="resetDate">
            {{ $formattedDate }}
        </span>
        <button wire:click="navigateDate('next')" class="btn btn-secondary">
            &rarr;
        </button>
    </div>
    <!-- Period Dropdown -->
    <div class="max-w-xl">
        <select
            id="period"
            wire:model="period"
            wire:change="changePeriod($event.target.value)"
            class="block w-full pl-3 pr-10 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        >
            @foreach ($periodOptions as $option)
                <option value="{{$option}}" {{ $period === $option ? 'selected' : '' }}>{{ ucfirst($option) }}</option>
            @endforeach
        </select>
    </div>
</div>