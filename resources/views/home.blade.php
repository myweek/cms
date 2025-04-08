<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="antialiased">
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <h1 class="text-xl font-bold">{{ config('app.name', 'Laravel') }}</h1>
                        </div>
                    </div>
                    <div class="flex items-center">
                        @auth
                            <span class="text-gray-700">{{ Auth::user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="ml-4">
                                @csrf
                                <button type="submit" class="text-gray-600 hover:text-gray-900">
                                    退出登录
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">
                                登录
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold mb-4">欢迎访问</h2>
                        <p class="text-gray-600">这是网站的前台首页。</p>
                        @auth
                            @if(Auth::user()->isAdmin())
                                <div class="mt-4">
                                    <a href="{{ route('admin.dashboard') }}" class="text-indigo-600 hover:text-indigo-900">
                                        进入后台管理
                                    </a>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </main>
        
        <footer class="bg-white border-t border-gray-200 mt-8">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-gray-500">
                    © {{ date('Y') }} {{ config('app.name', 'Laravel') }}. 保留所有权利。
                </p>
            </div>
        </footer>
    </div>
</body>
</html> 