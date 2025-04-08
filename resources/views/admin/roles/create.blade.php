@extends('admin.layouts.app')

@section('title', '创建角色')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">创建角色</h1>
            <a href="{{ route('admin.roles.index') }}" class="text-indigo-600 hover:text-indigo-900">
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
            <form action="{{ route('admin.roles.store') }}" method="POST" class="p-6 space-y-6">
                @csrf
                
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">角色名称</label>
                        <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <p class="mt-1 text-sm text-gray-500">用于系统识别的唯一标识，只能包含字母、数字、中划线和下划线</p>
                    </div>

                    <div>
                        <label for="display_name" class="block text-sm font-medium text-gray-700">显示名称</label>
                        <input type="text" name="display_name" id="display_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">角色描述</label>
                        <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">权限设置</h3>
                        <div class="space-y-4">
                            <!-- 基础权限 -->
                            <div class="border rounded-md p-4">
                                <h4 class="text-base font-medium text-gray-900 mb-3">基础权限</h4>
                                <div class="space-y-2">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="permissions[]" value="visit_dashboard" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-600">访问仪表盘</span>
                                    </label>
                                </div>
                            </div>

                            <!-- 内容管理权限 -->
                            <div class="border rounded-md p-4">
                                <h4 class="text-base font-medium text-gray-900 mb-3">内容管理</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <h5 class="text-sm font-medium text-gray-700">栏目管理</h5>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="create_category" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-600">创建栏目</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="view_categories" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-600">查看栏目</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="edit_category" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-600">编辑栏目</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="delete_category" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-600">删除栏目</span>
                                        </label>
                                    </div>

                                    <div class="space-y-2">
                                        <h5 class="text-sm font-medium text-gray-700">文章管理</h5>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="create_article" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-600">创建文章</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="view_articles" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-600">查看文章</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="edit_articles" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-600">编辑文章</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="delete_article" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-600">删除文章</span>
                                        </label>
                                    </div>

                                    <div class="space-y-2">
                                        <h5 class="text-sm font-medium text-gray-700">标签管理</h5>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="create_tag" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-600">创建标签</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="view_tags" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-600">查看标签</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="edit_tag" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-600">编辑标签</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="delete_tag" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-600">删除标签</span>
                                        </label>
                                    </div>

                                    <div class="space-y-2">
                                        <h5 class="text-sm font-medium text-gray-700">评论管理</h5>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="view_comments" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-600">查看评论</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="manage_comments" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-600">管理评论</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- 系统管理权限 -->
                            <div class="border rounded-md p-4">
                                <h4 class="text-base font-medium text-gray-900 mb-3">系统管理</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <h5 class="text-sm font-medium text-gray-700">用户管理</h5>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="create_user" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-600">创建用户</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="view_users" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-600">查看用户</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="edit_user" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-600">编辑用户</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="delete_user" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-600">删除用户</span>
                                        </label>
                                    </div>

                                    <div class="space-y-2">
                                        <h5 class="text-sm font-medium text-gray-700">角色管理</h5>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="create_role" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-600">创建角色</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="view_roles" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-600">查看角色</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="edit_role" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-600">编辑角色</span>
                                        </label>
                                        <br>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="delete_role" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-600">删除角色</span>
                                        </label>
                                    </div>

                                    <div class="space-y-2">
                                        <h5 class="text-sm font-medium text-gray-700">系统设置</h5>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="manage_settings" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-600">管理系统设置</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">启用</label>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        创建角色
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 