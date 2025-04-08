<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // 基础权限
            [
                'name' => '访问仪表盘',
                'slug' => 'visit_dashboard',
                'http_method' => 'GET',
                'http_path' => '/admin/dashboard',
                'description' => '允许访问管理后台仪表盘',
            ],

            // 栏目管理权限
            [
                'name' => '查看栏目列表',
                'slug' => 'view_categories',
                'http_method' => 'GET',
                'http_path' => '/admin/columns*',
                'description' => '允许查看栏目列表',
            ],
            [
                'name' => '创建栏目',
                'slug' => 'create_category',
                'http_method' => 'POST',
                'http_path' => '/admin/columns',
                'description' => '允许创建新栏目',
            ],
            [
                'name' => '编辑栏目',
                'slug' => 'edit_category',
                'http_method' => 'PUT,PATCH',
                'http_path' => '/admin/columns/*',
                'description' => '允许编辑现有栏目',
            ],
            [
                'name' => '删除栏目',
                'slug' => 'delete_category',
                'http_method' => 'DELETE',
                'http_path' => '/admin/columns/*',
                'description' => '允许删除栏目',
            ],

            // 文章管理权限
            [
                'name' => '查看文章列表',
                'slug' => 'view_articles',
                'http_method' => 'GET',
                'http_path' => '/admin/articles*',
                'description' => '允许查看文章列表',
            ],
            [
                'name' => '创建文章',
                'slug' => 'create_article',
                'http_method' => 'POST',
                'http_path' => '/admin/articles',
                'description' => '允许创建新文章',
            ],
            [
                'name' => '编辑文章',
                'slug' => 'edit_articles',
                'http_method' => 'PUT,PATCH',
                'http_path' => '/admin/articles/*',
                'description' => '允许编辑现有文章',
            ],
            [
                'name' => '删除文章',
                'slug' => 'delete_article',
                'http_method' => 'DELETE',
                'http_path' => '/admin/articles/*',
                'description' => '允许删除文章',
            ],

            // 标签管理权限
            [
                'name' => '查看标签列表',
                'slug' => 'view_tags',
                'http_method' => 'GET',
                'http_path' => '/admin/tags*',
                'description' => '允许查看标签列表',
            ],
            [
                'name' => '创建标签',
                'slug' => 'create_tag',
                'http_method' => 'POST',
                'http_path' => '/admin/tags',
                'description' => '允许创建新标签',
            ],
            [
                'name' => '编辑标签',
                'slug' => 'edit_tag',
                'http_method' => 'PUT,PATCH',
                'http_path' => '/admin/tags/*',
                'description' => '允许编辑现有标签',
            ],
            [
                'name' => '删除标签',
                'slug' => 'delete_tag',
                'http_method' => 'DELETE',
                'http_path' => '/admin/tags/*',
                'description' => '允许删除标签',
            ],

            // 评论管理权限
            [
                'name' => '查看评论列表',
                'slug' => 'view_comments',
                'http_method' => 'GET',
                'http_path' => '/admin/comments*',
                'description' => '允许查看评论列表',
            ],
            [
                'name' => '管理评论',
                'slug' => 'manage_comments',
                'http_method' => 'POST,PUT,PATCH,DELETE',
                'http_path' => '/admin/comments*',
                'description' => '允许管理评论（审核、编辑、删除等）',
            ],

            // 用户管理权限
            [
                'name' => '查看用户列表',
                'slug' => 'view_users',
                'http_method' => 'GET',
                'http_path' => '/admin/users*',
                'description' => '允许查看用户列表',
            ],
            [
                'name' => '创建用户',
                'slug' => 'create_user',
                'http_method' => 'POST',
                'http_path' => '/admin/users',
                'description' => '允许创建新用户',
            ],
            [
                'name' => '编辑用户',
                'slug' => 'edit_user',
                'http_method' => 'PUT,PATCH',
                'http_path' => '/admin/users/*',
                'description' => '允许编辑现有用户',
            ],
            [
                'name' => '删除用户',
                'slug' => 'delete_user',
                'http_method' => 'DELETE',
                'http_path' => '/admin/users/*',
                'description' => '允许删除用户',
            ],

            // 角色管理权限
            [
                'name' => '查看角色列表',
                'slug' => 'view_roles',
                'http_method' => 'GET',
                'http_path' => '/admin/roles*',
                'description' => '允许查看角色列表',
            ],
            [
                'name' => '创建角色',
                'slug' => 'create_role',
                'http_method' => 'POST',
                'http_path' => '/admin/roles',
                'description' => '允许创建新角色',
            ],
            [
                'name' => '编辑角色',
                'slug' => 'edit_role',
                'http_method' => 'PUT,PATCH',
                'http_path' => '/admin/roles/*',
                'description' => '允许编辑现有角色',
            ],
            [
                'name' => '删除角色',
                'slug' => 'delete_role',
                'http_method' => 'DELETE',
                'http_path' => '/admin/roles/*',
                'description' => '允许删除角色',
            ],

            // 系统设置权限
            [
                'name' => '管理系统设置',
                'slug' => 'manage_settings',
                'http_method' => 'GET,POST,PUT,PATCH',
                'http_path' => '/admin/settings*',
                'description' => '允许管理系统设置',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
