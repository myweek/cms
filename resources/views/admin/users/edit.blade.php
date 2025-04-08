@extends('admin.layouts.app')

@section('title', '编辑用户')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">编辑用户</h1>
            <a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:text-indigo-900">
                返回列表
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">用户名</label>
                    <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required
                        class="mt-1 block w-full rounded-md border-2 border-gray-300 bg-gray-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-12 px-4">
                    <p class="mt-1 text-sm text-gray-500">用户登录时使用的唯一标识</p>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">姓名</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="mt-1 block w-full rounded-md border-2 border-gray-300 bg-gray-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-12 px-4">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">邮箱</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="mt-1 block w-full rounded-md border-2 border-gray-300 bg-gray-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-12 px-4">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">密码</label>
                    <input type="password" name="password" id="password"
                        class="mt-1 block w-full rounded-md border-2 border-gray-300 bg-gray-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-12 px-4">
                    <p class="mt-1 text-sm text-gray-500">如果不修改密码请留空</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">确认密码</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="mt-1 block w-full rounded-md border-2 border-gray-300 bg-gray-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-12 px-4">
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">角色</label>
                    <select name="role" id="role" required
                        class="mt-1 block w-full rounded-md border-2 border-gray-300 bg-gray-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-12 px-4">
                        <option value="">请选择角色</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role', $user->roles->first()->id ?? '') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                                ({{ $role->description }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="is_active" class="flex items-center p-4 bg-gray-50 rounded-md border-2 border-gray-300">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                            class="rounded border-2 border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-5 w-5">
                        <span class="ml-2 text-sm text-gray-700">启用</span>
                    </label>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 text-base">
                        更新用户
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 