@extends('admin.layouts.app')

@section('title', '仪表盘')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">仪表盘</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- 统计卡片 -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">文章总数</h3>
            <p class="text-3xl font-bold text-indigo-600">0</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">用户总数</h3>
            <p class="text-3xl font-bold text-green-600">0</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">评论总数</h3>
            <p class="text-3xl font-bold text-yellow-600">0</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">今日访问</h3>
            <p class="text-3xl font-bold text-red-600">0</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
        <!-- 最近文章 -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">最近文章</h2>
            <div class="space-y-4">
                <p class="text-gray-500">暂无文章</p>
            </div>
        </div>
        
        <!-- 最近评论 -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">最近评论</h2>
            <div class="space-y-4">
                <p class="text-gray-500">暂无评论</p>
            </div>
        </div>
    </div>
</div>
@endsection 