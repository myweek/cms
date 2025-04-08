<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Column;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ArticleController extends Controller
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 显示文章列表
     */
    public function index(): View
    {
        $articles = Article::with(['column', 'author'])
            ->orderByDesc('is_top')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.articles.index', compact('articles'));
    }

    /**
     * 显示创建文章表单
     */
    public function create(): View
    {
        $columns = Column::where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('admin.articles.create', compact('columns'));
    }

    /**
     * 保存新文章
     */
    public function store(Request $request): RedirectResponse
    {
        // 调试请求内容
        \Log::info('Article store request data', [
            'all' => $request->all(),
            'has_content' => $request->has('content'),
            'content' => $request->input('content'),
        ]);
        
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'slug' => 'required|string|max:200|unique:articles',
            'content' => 'required',
            'excerpt' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,pending,published',
            'published_at' => 'nullable|date',
            'is_top' => 'boolean',
            'is_recommended' => 'boolean',
        ]);
        
        // 记录验证后的数据
        \Log::info('Article validated data', $validated);

        // 处理封面图片
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('articles/covers', 'public');
        }

        // 设置作者ID为当前登录用户
        $validated['author_id'] = Auth::id();
        
        // 处理复选框的值
        $validated['is_top'] = $request->boolean('is_top');
        $validated['is_recommended'] = $request->boolean('is_recommended');

        // 创建文章
        $article = Article::create($validated);
        \Log::info('Article created', ['id' => $article->id]);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', '文章创建成功！');
    }

    /**
     * 显示编辑文章表单
     */
    public function edit(Article $article): View
    {
        return view('admin.articles.edit', compact('article'));
    }

    /**
     * 更新文章
     */
    public function update(Request $request, Article $article): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'slug' => 'required|string|max:200|unique:articles,slug,' . $article->id,
            'content' => 'required',
            'excerpt' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,pending,published',
            'published_at' => 'nullable|date',
            'is_top' => 'boolean',
            'is_recommended' => 'boolean',
        ]);

        // 处理封面图片
        if ($request->hasFile('cover_image')) {
            // 删除旧封面
            if ($article->cover_image) {
                Storage::disk('public')->delete($article->cover_image);
            }
            // 保存新封面
            $validated['cover_image'] = $request->file('cover_image')->store('articles/covers', 'public');
        }

        // 处理复选框的值
        $validated['is_top'] = $request->boolean('is_top');
        $validated['is_recommended'] = $request->boolean('is_recommended');

        // 更新文章
        $article->update($validated);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', '文章更新成功！');
    }

    /**
     * 删除文章
     */
    public function destroy(Article $article): RedirectResponse
    {
        // 删除封面图片
        if ($article->cover_image) {
            Storage::disk('public')->delete($article->cover_image);
        }

        // 删除文章
        $article->delete();

        return redirect()
            ->route('admin.articles.index')
            ->with('success', '文章删除成功！');
    }
}
