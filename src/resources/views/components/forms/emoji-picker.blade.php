@props(['name', 'value' => '🔥'])

<div class="flex gap-4 items-start relative">
    <input 
        id="{{ $name }}" 
        name="{{ $name }}" 
        type="text"
        value="{{ $value }}"
        class="text-2xl cursor-pointer text-grey bg-dark-grey border-1 text-center w-14 py-2 border-grey rounded-lg focus:outline-none focus:ring-0 focus:ring-offset-0"
    />
    {{-- Emoji Picker --}}
    <emoji-picker 
        id="emoji-picker" 
        class="absolute top-full mt-2 hidden"
    ></emoji-picker>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('{!! $name !!}');
        const picker = document.getElementById('emoji-picker');
        let isPickerClicked = false;

        // Show the picker when the input is focused
        input.addEventListener('focus', () => {
            picker.classList.remove('hidden');
        });

        // Detect clicks inside the picker
        picker.addEventListener('mousedown', () => {
            isPickerClicked = true; // Prevent immediate hiding
        });

        // Hide the picker when clicking outside of it or the input
        document.addEventListener('mousedown', (event) => {
            if (!input.contains(event.target) && !picker.contains(event.target) && !isPickerClicked) {
                picker.classList.add('hidden');
            }
            isPickerClicked = false; // Reset after click
        });

        // Update input value on emoji selection
        picker.addEventListener('emoji-click', (event) => {
            input.value = event.detail.unicode;
            picker.classList.add('hidden'); // Hide picker after selection
        });

        // Prevent typing anything other than emojis
        input.addEventListener('input', (event) => {
            const emojiRegex = /^[\p{Emoji_Presentation}]+$/u; // Matches emojis only
            if (!emojiRegex.test(input.value)) {
                input.value = ''; // Clear invalid input
            }
        });

        // Prevent invalid characters on keypress
        input.addEventListener('keypress', (event) => {
            const emojiRegex = /^[\p{Emoji_Presentation}]+$/u; // Matches emojis only
            if (!emojiRegex.test(event.key)) {
                event.preventDefault(); // Block invalid key
            }
        });
    });
</script>

<style>
    emoji-picker {
        width: 300px;
        height: 300px;
        z-index: 10;
        border-radius: 8px;
    }
</style>
