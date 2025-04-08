@extends('admin.layouts.app')

@section('title', '文章管理')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">文章管理</h1>
        <a href="{{ route('admin.articles.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
            创建文章
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">标题</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">栏目</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">作者</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">状态</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">发布时间</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($articles as $article)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $article->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center">
                                @if ($article->cover_image)
                                    <img src="{{ asset('storage/' . $article->cover_image) }}" alt="{{ $article->title }}" class="h-10 w-10 mr-3 object-cover rounded">
                                @endif
                                <div>
                                    <div class="font-medium">{{ $article->title }}</div>
                                    @if ($article->is_top || $article->is_recommended)
                                        <div class="flex mt-1 space-x-1">
                                            @if ($article->is_top)
                                                <span class="px-1.5 py-0.5 text-xs bg-red-100 text-red-800 rounded">置顶</span>
                                            @endif
                                            @if ($article->is_recommended)
                                                <span class="px-1.5 py-0.5 text-xs bg-blue-100 text-blue-800 rounded">推荐</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $article->column->name ?? '无栏目' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $article->author->name ?? '未知' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClasses = [
                                    'draft' => 'bg-yellow-100 text-yellow-800',
                                    'pending' => 'bg-blue-100 text-blue-800',
                                    'published' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                ];
                                $statusTexts = [
                                    'draft' => '草稿',
                                    'pending' => '待审核',
                                    'published' => '已发布',
                                    'rejected' => '已拒绝',
                                ];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$article->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusTexts[$article->status] ?? $article->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $article->published_at ? $article->published_at->format('Y-m-d H:i') : '未发布' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.articles.edit', $article) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">编辑</a>
                            <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('确定要删除这篇文章吗？')">删除</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">暂无文章</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $articles->links() }}
    </div>
</div>
@endsection 