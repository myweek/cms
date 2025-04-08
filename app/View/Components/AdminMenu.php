<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class AdminMenu extends Component
{
    public array $menuItems;

    public function __construct()
    {
        $this->menuItems = $this->getMenuItems();
    }

    private function getMenuItems(): array
    {
        $user = Auth::user();
        
        $items = [
            [
                'title' => '仪表盘',
                'icon' => 'fa-tachometer-alt',
                'route' => 'admin.dashboard',
                'permission' => 'visit_dashboard',
            ],
            [
                'title' => '内容管理',
                'icon' => 'fa-file-alt',
                'children' => [
                    [
                        'title' => '网站栏目管理',
                        'icon' => 'fa-folder',
                        'route' => 'admin.columns.index',
                        'permission' => 'view_categories', // 查看分类列表
                    ],
                    [
                        'title' => '文章管理',
                        'icon' => 'fa-newspaper',
                        'route' => 'admin.articles.index',
                        'permission' => 'view_articles', // 查看文章列表
                    ],
                    [
                        'title' => '标签管理',
                        'icon' => 'fa-tags',
                        'route' => 'admin.tags.index',
                        'permission' => 'view_tags', // 查看标签列表
                    ],
                    [
                        'title' => '评论管理',
                        'icon' => 'fa-comments',
                        'route' => 'admin.comments.index',
                        'permission' => 'view_comments', // 查看评论列表
                    ],
                ],
            ],
            [
                'title' => '系统管理',
                'icon' => 'fa-cog',
                'children' => [
                    [
                        'title' => '用户管理',
                        'icon' => 'fa-users',
                        'route' => 'admin.users.index',
                        'permission' => 'view_users', // 查看用户列表
                    ],
                    [
                        'title' => '角色管理',
                        'icon' => 'fa-user-tag',
                        'route' => 'admin.roles.index',
                        'permission' => 'view_roles', // 查看角色列表
                    ],
                    [
                        'title' => '系统设置',
                        'icon' => 'fa-cog',
                        'route' => 'admin.settings.index',
                        'permission' => 'manage_settings', // 管理系统设置
                    ],
                ],
            ],
        ];

        // 如果不是超级管理员，过滤掉没有权限的菜单项
        if (!$user->isAdmin()) {
            $items = array_map(function ($item) use ($user) {
                if (isset($item['children'])) {
                    $item['children'] = array_filter($item['children'], function ($child) use ($user) {
                        return !$child['permission'] || $user->hasPermission($child['permission']);
                    });
                } else {
                    // 检查单个菜单项的权限
                    if ($item['permission'] && !$user->hasPermission($item['permission'])) {
                        return null;
                    }
                }
                return $item;
            }, $items);

            // 移除没有子菜单的父菜单和没有权限的单个菜单
            $items = array_filter($items, function ($item) {
                return $item !== null && (!isset($item['children']) || !empty($item['children']));
            });
        }

        return $items;
    }

    public function render()
    {
        return view('components.admin-menu');
    }
} 