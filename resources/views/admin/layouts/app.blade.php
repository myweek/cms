<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>

    <!-- 字体 -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @yield('styles')
</head>
<body class="antialiased bg-gray-100">
    <div class="min-h-screen">
        <!-- 顶部导航栏 -->
        <nav class="bg-indigo-600 text-white shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <span class="text-xl font-bold">{{ config('app.name', 'Laravel') }}</span>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="ml-3 relative">
                            <div>
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md hover:bg-indigo-700 focus:outline-none transition ease-in-out duration-150">
                                        {{ Auth::user()->name ?? '访客' }}
                                        <i class="fas fa-user ml-2"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        @auth
                            <form method="POST" action="{{ route('logout') }}" class="ml-4">
                                @csrf
                                <button type="submit" class="px-3 py-2 rounded-md hover:bg-indigo-700">
                                    <i class="fas fa-sign-out-alt mr-1"></i> 退出
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex">
            <!-- 左侧边栏 -->
            <aside class="w-64 bg-white shadow-lg min-h-screen">
                <x-admin-menu />
            </aside>

            <!-- 主内容区 -->
            <main class="flex-1 p-8">
                @yield('content')
            </main>
        </div>
    </div>
    
    @yield('scripts')
</body>
</html> 