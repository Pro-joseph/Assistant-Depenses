@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl'
])

@php
$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth];
@endphp

<input type="checkbox" id="modal-{{ $name }}" class="hidden peer" {{ $show ? 'checked' : '' }}>

<div class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50 hidden peer-checked:block">
    <label for="modal-{{ $name }}" class="fixed inset-0 bg-gray-500 opacity-75 cursor-pointer"></label>

    <div class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full {{ $maxWidth }} sm:mx-auto">
        {{ $slot }}
    </div>
</div>
