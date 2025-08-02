@props([
    'name' => 'password',
    'required' => true,
    'placeholder' => '',
    'disabled' => false,
])

<div
    x-data="{
        value: '',
        showPassword: false
    }"
    class="relative"
>
    <input
        x-model="value"
        :type="showPassword ? 'text' : 'password'"
        id="{{ $name }}" 
        name="{{ $name }}" 
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        class="text-sm w-full rounded-lg outline-none bg-tertiary-grey border-2 border-secondary-grey shadow-md"
    />
    <i
        x-show="!showPassword && value.length > 0"
        x-cloak
        @click="showPassword = !showPassword"
        class="fa-solid fa-eye absolute top-3 right-4 cursor-pointer"
    ></i>
    <i
        x-show="showPassword && value.length > 0"
        x-cloak
        @click="showPassword = !showPassword"
        class="fa-solid fa-eye-slash absolute top-3 right-4 cursor-pointer"
    ></i>
</div>