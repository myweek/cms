<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * 显示角色列表
     */
    public function index(): View
    {
        $roles = Role::orderByDesc('created_at')
            ->paginate(10);

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * 显示创建角色表单
     */
    public function create(): View
    {
        $permissions = Permission::orderBy('name')->get();

        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * 保存新角色
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:roles',
            'display_name' => 'required|string|max:50',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'string',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        if (isset($validated['permissions'])) {
            $permissions = Permission::whereIn('slug', $validated['permissions'])->get();
            $role->permissions()->attach($permissions->pluck('id')->toArray());
        }

        return redirect()
            ->route('admin.roles.index')
            ->with('success', '角色创建成功！');
    }

    /**
     * 显示编辑角色表单
     */
    public function edit(Role $role): View
    {
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * 更新角色
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:roles,name,' . $role->id,
            'display_name' => 'required|string|max:50',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'string',
        ]);

        $role->update([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        if (isset($validated['permissions'])) {
            $permissions = Permission::whereIn('slug', $validated['permissions'])->get();
            $role->permissions()->sync($permissions->pluck('id')->toArray());
        } else {
            $role->permissions()->detach();
        }

        return redirect()
            ->route('admin.roles.index')
            ->with('success', '角色更新成功！');
    }

    /**
     * 删除角色
     */
    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', '角色删除成功！');
    }
}
