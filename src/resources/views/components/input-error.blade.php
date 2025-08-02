@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'mt-2 text-sm bg-red-600 py-1 px-3 text-white rounded']) }}>{{ $message }}</p>
@enderror
