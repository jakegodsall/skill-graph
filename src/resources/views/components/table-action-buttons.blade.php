<div class="flex gap-4">
    <!-- Show Button -->
    @if (isset($showRoute) && $canShow)
        <a href="{{ route($showRoute, [$modelIdKey => $model->id]) }}" class="text-gray-500 hover:text-gray-600">
            <i class="fa-fw fa-solid fa-eye"></i>
        </a>
    @endif

    <!-- Edit Button -->
    @if (isset($editRoute) && $canEdit)
        <a href="{{ route($editRoute, [$modelIdKey => $model->id]) }}" class="text-gray-500 hover:text-gray-600">
            <i class="fa-fw fa-solid fa-pen-to-square"></i>
        </a>
    @endif

    <!-- Delete Button -->
    @if (isset($deleteEvent) && $canDelete)
        <button onclick="if(confirm('{{ $confirmMessage }}')) Livewire.emit('{{ $deleteEvent }}', {{ $model->id }})"
                class="text-gray-500 hover:text-gray-600">
            <i class="fa-fw fa-solid fa-trash"></i>
        </button>
    @endif
</div>
