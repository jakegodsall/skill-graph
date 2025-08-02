<div class="flex gap-4">
    <!-- Show Button -->
    <div class="flex gap-4 relative" x-data="{ show: false }">
        @if (isset($showRoute) && $canShow)
            <!-- Show Button -->
            <a @mouseenter="show = true" @mouseleave="show = false"
               href="{{ route($showRoute, [$modelIdKey => $model->id]) }}"
               class="text-gray-500 hover:text-gray-600 relative">
                <i class="fa-fw fa-solid fa-eye"></i> Show
            </a>
    
            <!-- Tooltip -->
            <span x-show="show"
                x-cloak
                x-transition
                class="absolute top-6 -left-4 p-2 text-xs text-white bg-gray-700 rounded-md shadow-md z-10">
                Show
            </span>
        @endif
    </div>
    

    <!-- Edit Button -->
    <div class="flex gap-4 relative" x-data="{ show: false }">
        @if (isset($editRoute) && $canEdit)
            <a @mouseenter="show = true" @mouseleave="show = false"
                href="{{ route($editRoute, [$modelIdKey => $model->id]) }}"
                class="text-gray-500 hover:text-gray-600">
                <i class="fa-fw fa-solid fa-pen-to-square"></i>
            </a>

            <!-- Tooltip -->
            <span x-show="show"
                x-cloak
                x-transition
                class="absolute top-6 -left-4 p-2 text-xs text-white bg-gray-700 rounded-md shadow-md z-10">
                Edit
            </span>
        @endif
    </div>

    <!-- Delete Button -->
    <div class="flex gap-4 relative" x-data="{ show: false }">
        @if (isset($deleteEvent) && $canDelete)
            <button 
                @mouseenter="show = true" @mouseleave="show = false"
                onclick="if(confirm('{{ $confirmMessage }}')) Livewire.emit('{{ $deleteEvent }}', {{ $model->id }})"
                    class="text-gray-500 hover:text-gray-600">
                <i class="fa-fw fa-solid fa-trash"></i>
            </button>

            <!-- Tooltip -->
            <span x-show="show"
                x-cloak
                x-transition
                class="absolute top-6 -left-4 p-2 text-xs text-white bg-gray-700 rounded-md shadow-md z-10">
                Delete
            </span>
        @endif
    </div>
</div>
