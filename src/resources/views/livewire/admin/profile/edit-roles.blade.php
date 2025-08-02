<x-form-section submit="updateRole">
    <x-slot name="title">
        {{ __('Select Roles') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Roles can control what areas of the system the user has access to. Super Admins automatically have access to the full system.') }}
    </x-slot>

    <x-slot name="form">
        @if (count($this->roles) > 0)
            <div class="col-span-6 lg:col-span-4">
                <label for="role" value="{{ __('Roles') }}" />
                <x-input-error for="role" class="mt-2" />

                <div class="relative z-0 mt-1 border border-gray-200 rounded-lg cursor-pointer">
                    @foreach ($this->roles as $index => $role)
                        <button type="button" class="relative px-4 py-3 inline-flex w-full rounded-lg bg-white focus:z-10 focus:outline-none focus:border-blue focus:border-opacity-50 focus:ring focus:ring-blue focus:ring-opacity-20 {{ $index > 0 ? 'border-t border-gray-200 rounded-t-none' : '' }} {{ ! $loop->last ? 'rounded-b-none' : '' }}"
                                wire:click="toggleRole('{{ $role }}')">
                            <div class="{{ isset($this->current_roles) && !in_array($role, $this->current_roles) ? 'opacity-50' : '' }}">
                                <!-- Role Name -->
                                <div class="flex items-center">
                                    <div class="text-sm text-gray-600">
                                        {{ $role }}
                                    </div>

                                    @if (in_array($role, $this->current_roles))
                                        <svg class="ml-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @endif
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        @endif
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>
