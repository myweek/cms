<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 清除现有的角色权限关联
        DB::table('role_permission')->truncate();
        DB::table('user_role')->truncate();

        // 创建超级管理员角色
        $adminRole = Role::updateOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'admin',
                'display_name' => '超级管理员',
                'description' => '系统超级管理员，拥有所有权限',
                'is_active' => true,
            ]
        );

        // 为超级管理员分配所有权限
        $permissions = Permission::all();
        $adminRole->permissions()->sync($permissions->pluck('id')->toArray());

        // 创建编辑角色
        $editorRole = Role::updateOrCreate(
            ['slug' => 'editor'],
            [
                'name' => 'editor',
                'display_name' => '编辑',
                'description' => '内容编辑，负责内容管理',
                'is_active' => true,
            ]
        );

        // 为编辑分配相应权限
        $editorPermissions = Permission::whereIn('slug', [
            'visit_dashboard',
            'view_categories',
            'create_category',
            'edit_category',
            'delete_category',
            'view_articles',
            'create_article',
            'edit_articles',
            'delete_article',
            'view_tags',
            'create_tag',
            'edit_tag',
            'delete_tag',
            'view_comments',
            'manage_comments'
        ])->get();
        $editorRole->permissions()->sync($editorPermissions->pluck('id')->toArray());

        // 为管理员用户分配超级管理员角色
        $adminUser = User::where('role', 'admin')->first();
        if ($adminUser) {
            $adminUser->roles()->sync([$adminRole->id]);
        }

        // 为编辑用户分配编辑角色
        User::where('role', 'editor')->get()->each(function ($user) use ($editorRole) {
            $user->roles()->sync([$editorRole->id]);
        });
    }
}
