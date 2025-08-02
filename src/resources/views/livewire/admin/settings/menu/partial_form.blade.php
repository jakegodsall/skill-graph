@if($errors->any())
    <x-alert class="mb-5" type="danger" icon="fas fa-exclamation-circle">{{ __('Please fix the errors below and try again') }}</x-alert>
@endif

<h2 class="h2 mb-5">{{ __('Details') }}</h2>

<div class="lg:flex mb-5">
    <x-label for="title" class="lg:w-1/5 flex-shrink-0">{{ __('Title') }}*</x-label>
    <div class="flex-grow">
        <x-input type="text" id="title" name="title" :class="$errors->has('title') ? 'is-invalid' : ''" :value="old('title', $menuitem->title)" />
        <x-input-error for="title" class="mt-2" />
    </div>
</div>

<div class="lg:flex mb-5">
    <x-label for="icon" class="lg:w-1/5 flex-shrink-0">{{ __('Icon') }}</x-label>
    <div class="flex-grow">
        <x-fa-icon id="icon" name="icon" :class="$errors->has('icon') ? 'is-invalid' : ''">
            @if($oldicon = old('icon', $menuitem->icon))
                <option>{{ $oldicon }}</option>
            @endif
        </x-fa-icon>
        <small>{{ __('Full list of items can be found here:') }} <a href="https://fontawesome.com/icons?d=gallery&s=solid" class="link" target="_blank">https://fontawesome.com/icons?d=gallery&s=solid</a></small>
        <x-input-error for="icon" class="mt-2" />
    </div>
</div>

<div class="lg:flex mb-5">
    <x-label for="active" class="lg:w-1/5 flex-shrink-0">{{ __('Active') }}</x-label>
    <div class="flex-grow">
        <x-yesno id="active" name="active" :value="old('active', $menuitem->active)" />
    </div>
</div>

<hr class="border-t my-5">

<h2 class="h2 mb-5">{{ __('Link') }}</h2>

<div class="lg:flex mb-5">
    <x-label for="dropdownOnly" class="lg:w-1/5 flex-shrink-0">{{ __('Dropdown Only?') }}</x-label>
    <div class="flex-grow">
        <x-yesno id="dropdownOnly" name="dropdownOnly" :value="old('dropdownOnly', $menuitem->dropdownOnly)" />
    </div>
</div>

<div class="lg:flex mb-5">
    <x-label for="internal" class="lg:w-1/5 flex-shrink-0">{{ __('Internal Link?') }}</x-label>
    <div class="flex-grow">
        <x-yesno id="internal" name="internal" :value="old('internal', $menuitem->internal)" />
    </div>
</div>

<div class="lg:flex mb-5">
    <x-label for="link" class="lg:w-1/5 flex-shrink-0">{{ __('Link') }}</x-label>
    <div class="flex-grow">
        <x-input type="text" id="link" name="link" :class="$errors->has('link') ? 'is-invalid' : ''" :value="old('link', $menuitem->link)" />
        <x-input-error for="link" class="mt-2" />
    </div>
</div>

<hr class="border-t my-5">

<h2 class="h2 mb-5">{{ __('Global Permissions') }}</h2>

<div x-data="{all: {{ $menuitem->all_permissions ? 'true' : 'false' }}}">
    <div class="lg:flex mb-5">
        <x-label for="all_permissions" class="lg:w-1/5 flex-shrink-0">{{ __('Any Permission?') }}</x-label>
        <div class="flex-grow">
            <x-yesno id="all_permissions" name="all_permissions" @change-yes="all = true" @change-no="all = false" :value="old('all_permissions', $menuitem->all_permissions)" />
        </div>
    </div>

    <div class="mb-5" x-show="!all">
        <p class="alert alert-warning mb-5">{{ __('This uses OR logic. This means the menu item will show if the user has any one of the selected permissions.') }}</p>
        @foreach($permissions as $cat_name => $cat)
            <h2 class="h2 mb-5">{{ $cat['name'] }}</h2>
            @if(isset($cat['perms']))
            <div class="grid grid-cols-3 gap-3">
                @foreach($cat['perms'] as $perm_name => $perm)
                    <div>
                        <div class="inline-flex">
                            <x-checkbox name="permissions[]" id="permissions_{{ str_replace(' ', '_', $perm_name) }}" class="my-3 mr-2" :value="$perm_name" :checked="in_array($perm_name, old('permissions', $current_permissions))" />
                            <x-label for="permissions_{{ str_replace(' ', '_', $perm_name) }}">
                                {{ $perm['name'] }}<br>
                                <span class="text-sm text-gray-400">{!! $perm['desc'] !!}</span>
                            </x-label>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif

            @foreach($cat['children'] ?? [] as $subcat_name => $subcat)
                <div class="mt-6 p-3 {{ $loop->index % 2 == 0 ? '' : 'bg-gray-50' }}">
                    <h2 class="font-medium text-primary mb-3">{{ $subcat['name'] }}</h2>
                    @if(isset($subcat['perms']))
                    <div class="grid grid-cols-3 gap-3">
                        @foreach($subcat['perms'] as $perm_name => $perm)
                            <div>
                                <div class="inline-flex">
                                    <x-checkbox name="permissions[]" id="permissions_{{ str_replace(' ', '_', $perm_name) }}" class="my-3 mr-2" :value="$perm_name" :checked="in_array($perm_name, old('permissions', $current_permissions))" />
                                    <x-label for="permissions_{{ str_replace(' ', '_', $perm_name) }}">
                                        {{ $perm['name'] }}<br>
                                        @if(isset($perm['desc'])) <span class="text-sm text-gray-400">{!! $perm['desc'] !!}</span> @endif
                                    </x-label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            @endforeach

            <hr class="border-t my-5">
        @endforeach
    </div>
</div>
<input type="hidden" name="type" value="{{ ($menuitem->exists ? ($menuitem->type == 'Admin') : (request('tab') == 'admin')) ? 'Admin' : 'User' }}" />

<hr class="border-t my-5">

<div class="flex justify-end items-end pt-3">
    <x-button class="btn btn-blue">{{ __('Save') }}</x-button>
</div>
