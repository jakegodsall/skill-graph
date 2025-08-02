@props([
    'type' => 'button'
])

<button
    type={{ $type }} 
    class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-transform transform duration-300 hover:scale-105">
    {{ $slot }}
</button>