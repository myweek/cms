@props(['route', 'icon', 'permission' => null])

@php
    $isActive = request()->routeIs($route.'*');
    $shouldShow = !$permission || (auth()->check() && auth()->user()->hasPermission($permission));
@endphp

@if($shouldShow)
    <a href="{{ route($route) }}"
        @class([
            'flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150',
            'text-gray-900 bg-gray-100' => $isActive,
            'text-gray-600 hover:text-gray-900 hover:bg-gray-50' => !$isActive,
        ])>
        <i class="{{ $icon }} w-5 h-5 mr-3"></i>
        <span>{{ $slot }}</span>
    </a>
@endif 