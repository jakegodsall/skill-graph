<div class="flex gap-4 items-start">
    <div 
        id="{{ $name }}-selected" 
        class="text-2xl cursor-pointer text-grey bg-transparent border-1 w-14 py-2 border-1 border-grey rounded-lg focus:outline-none focus:ring-0 focus:ring-offset-0 h-12"
        style="background-color: {{ $selectedColor }};"
        wire:click="openModal"
    ></div>

    {{-- Hidden Input to Submit the Selected Color --}}
    <input type="hidden" name="{{ $name }}" value="{{ $selectedColor }}">

    {{-- Modal --}}
    @if ($isModalOpen)
        <div 
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
            wire:click="closeModal"
        >
            <div 
                class="bg-dark-grey rounded-lg mx-4 p-6 w-96"
                wire:click.stop
            >
                <h2 class="text-xl text-light-grey font-semibold mb-4">Pick a Color</h2>
                <div class="grid grid-cols-4 gap-4">
                    @foreach ($colors as $color)
                        <button 
                            type="button" 
                            class="w-10 h-10 rounded-full border {{ $color === $selectedColor ? 'outline-white outline-2' : 'border-darkest-grey' }}"
                            style="background-color: {{ $color }};"
                            wire:click="selectColor('{{ $color }}')"
                        ></button>
                    @endforeach
                </div>
                <div class="flex justify-end mt-6">
                    <button 
                        type="button" 
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg mr-2"
                        wire:click="closeModal"
                    >
                        Cancel
                    </button>
                    <button 
                        type="button" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg"
                        wire:click="closeModal"
                    >
                        Done
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
