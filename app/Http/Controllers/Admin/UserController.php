<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    /**
     * 显示用户列表
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $users = User::with('roles')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * 显示创建用户表单
     *
     * @return View
     */
    public function create(): View
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * 保存新用户
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,id',
            'is_active' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'username' => $validated['username'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'is_active' => $request->boolean('is_active', true),
            ]);

            $user->roles()->sync([$validated['role']]);

            DB::commit();

            return redirect()
                ->route('admin.users.index')
                ->with('success', '用户创建成功');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => '用户创建失败：' . $e->getMessage()]);
        }
    }

    /**
     * 显示编辑用户表单
     *
     * @param User $user
     * @return View
     */
    public function edit(User $user): View
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * 更新用户信息
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,id',
            'is_active' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $updateData = [
                'username' => $validated['username'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'is_active' => $request->boolean('is_active', true),
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);
            $user->roles()->sync([$validated['role']]);

            DB::commit();

            return redirect()
                ->route('admin.users.index')
                ->with('success', '用户更新成功');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => '用户更新失败：' . $e->getMessage()]);
        }
    }

    /**
     * 删除用户
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        try {
            if ($user->id === auth()->id()) {
                throw new \Exception('不能删除当前登录用户');
            }

            DB::beginTransaction();
            
            $user->roles()->detach();
            $user->delete();
            
            DB::commit();

            return redirect()
                ->route('admin.users.index')
                ->with('success', '用户删除成功');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => '用户删除失败：' . $e->getMessage()]);
        }
    }
}
