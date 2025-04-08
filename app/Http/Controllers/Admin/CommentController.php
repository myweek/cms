<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view_comments')->only(['index', 'show']);
        $this->middleware('permission:manage_comments')->only(['edit', 'update', 'destroy']);
    }

    /**
     * 显示评论列表
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view_comments');
        
        $comments = Comment::with(['article', 'user'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.comments.index', compact('comments'));
    }

    /**
     * 显示评论详情
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Comment $comment): View
    {
        $this->authorize('view_comments');
        
        return view('admin.comments.show', compact('comment'));
    }

    /**
     * 显示编辑评论表单
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Comment $comment): View
    {
        $this->authorize('manage_comments');
        
        return view('admin.comments.edit', compact('comment'));
    }

    /**
     * 更新评论
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Comment $comment): RedirectResponse
    {
        $this->authorize('manage_comments');
        
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $comment->update($validated);

        return redirect()
            ->route('admin.comments.index')
            ->with('success', '评论更新成功！');
    }

    /**
     * 删除评论
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        $this->authorize('manage_comments');
        
        $comment->delete();

        return redirect()
            ->route('admin.comments.index')
            ->with('success', '评论删除成功！');
    }
}
