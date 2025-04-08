<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Column;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ColumnController extends Controller
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 显示栏目列表
     */
    public function index(): View
    {
        $columns = Column::orderBy('order')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.columns.index', compact('columns'));
    }

    /**
     * 显示创建栏目表单
     */
    public function create(): View
    {
        $columns = Column::where('parent_id', 0)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('admin.columns.create', compact('columns'));
    }

    /**
     * 保存新栏目
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'slug' => 'required|string|max:50|unique:categories',
            'description' => 'nullable|string|max:1000',
            'parent_id' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        Column::create($validated);

        return redirect()
            ->route('admin.columns.index')
            ->with('success', '栏目创建成功！');
    }

    /**
     * 显示编辑栏目表单
     */
    public function edit(Column $column): View
    {
        $columns = Column::where('parent_id', 0)
            ->where('id', '!=', $column->id)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('admin.columns.edit', compact('column', 'columns'));
    }

    /**
     * 更新栏目
     */
    public function update(Request $request, Column $column): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'slug' => 'required|string|max:50|unique:categories,slug,' . $column->id,
            'description' => 'nullable|string|max:1000',
            'parent_id' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $column->update($validated);

        return redirect()
            ->route('admin.columns.index')
            ->with('success', '栏目更新成功！');
    }

    /**
     * 删除栏目
     */
    public function destroy(Column $column): RedirectResponse
    {
        $column->delete();

        return redirect()
            ->route('admin.columns.index')
            ->with('success', '栏目删除成功！');
    }
}
