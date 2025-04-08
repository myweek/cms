<nav class="mt-5 px-2 space-y-1">
    @foreach($menuItems as $item)
        @if(isset($item['children']))
            <!-- 菜单组 -->
            <div class="py-2" x-data="{ open: true }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider hover:bg-gray-50 rounded-md">
                    <span>{{ $item['title'] }}</span>
                    <i class="fas transition-transform duration-300" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                </button>

                <div class="mt-2 space-y-1 overflow-hidden transition-all duration-300 ease-in-out"
                     x-show="open"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="transform opacity-0 -translate-y-4"
                     x-transition:enter-end="transform opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="transform opacity-100 translate-y-0"
                     x-transition:leave-end="transform opacity-0 -translate-y-4">
                    @foreach($item['children'] as $child)
                        <a href="{{ route($child['route']) }}" 
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs($child['route'].'*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas {{ $child['icon'] }} mr-3 {{ request()->routeIs($child['route'].'*') ? 'text-indigo-700' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                            {{ $child['title'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            <!-- 单个菜单项 -->
            <a href="{{ route($item['route']) }}" 
               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs($item['route'].'*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <i class="fas {{ $item['icon'] }} mr-3 {{ request()->routeIs($item['route'].'*') ? 'text-indigo-700' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                {{ $item['title'] }}
            </a>
        @endif
    @endforeach
</nav> 