<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends Controller
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view_tags')->only(['index']);
        $this->middleware('permission:create_tag')->only(['create', 'store']);
        $this->middleware('permission:edit_tag')->only(['edit', 'update']);
        $this->middleware('permission:delete_tag')->only(['destroy']);
    }

    /**
     * 显示标签列表
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view_tags');
        
        $tags = Tag::withCount('articles')
            ->orderByDesc('articles_count')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.tags.index', compact('tags'));
    }

    /**
     * 显示创建标签表单
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('create_tag');
        
        return view('admin.tags.create');
    }

    /**
     * 保存新标签
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create_tag');
        
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:tags',
            'slug' => 'required|string|max:50|unique:tags',
            'description' => 'nullable|string|max:1000',
        ]);

        Tag::create($validated);

        return redirect()
            ->route('admin.tags.index')
            ->with('success', '标签创建成功！');
    }

    /**
     * 显示编辑标签表单
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Tag $tag): View
    {
        $this->authorize('edit_tag');
        
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * 更新标签
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Tag $tag): RedirectResponse
    {
        $this->authorize('edit_tag');
        
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:tags,name,' . $tag->id,
            'slug' => 'required|string|max:50|unique:tags,slug,' . $tag->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $tag->update($validated);

        return redirect()
            ->route('admin.tags.index')
            ->with('success', '标签更新成功！');
    }

    /**
     * 删除标签
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Tag $tag): RedirectResponse
    {
        $this->authorize('delete_tag');
        
        $tag->delete();

        return redirect()
            ->route('admin.tags.index')
            ->with('success', '标签删除成功！');
    }
}
