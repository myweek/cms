@extends('admin.layouts.app')

@section('title', '编辑分类')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">编辑分类</h1>
            <a href="{{ route('admin.categories.index') }}" class="text-indigo-600 hover:text-indigo-900">
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
            <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">分类名称</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700">分类别名</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="mt-1 text-sm text-gray-500">用于 URL 的唯一标识，只能包含字母、数字、中划线和下划线</p>
                </div>

                <div>
                    <label for="parent_id" class="block text-sm font-medium text-gray-700">父级分类</label>
                    <select name="parent_id" id="parent_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="0">无</option>
                        @foreach($categories as $item)
                            <option value="{{ $item->id }}" {{ old('parent_id', $category->parent_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">分类描述</label>
                    <textarea name="description" id="description" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $category->description) }}</textarea>
                </div>

                <div>
                    <label for="order" class="block text-sm font-medium text-gray-700">排序</label>
                    <input type="number" name="order" id="order" value="{{ old('order', $category->order) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="mt-1 text-sm text-gray-500">数字越小越靠前</p>
                </div>

                <div>
                    <label for="is_active" class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700">启用</span>
                    </label>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        更新分类
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 