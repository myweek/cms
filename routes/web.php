<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ColumnController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ImageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 登录相关路由
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

// 需要登录的路由
Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    
    // 后台路由组
    Route::prefix('admin')
        ->name('admin.')
        ->group(function () {
            // 仪表盘
            Route::get('dashboard', [DashboardController::class, 'index'])
                ->name('dashboard')
                ->middleware('permission:visit_dashboard');
            
            // 内容管理路由组
            Route::group(['prefix' => 'columns', 'as' => 'columns.'], function () {
                Route::get('/', [ColumnController::class, 'index'])
                    ->name('index')
                    ->middleware('permission:view_categories');
                Route::get('/create', [ColumnController::class, 'create'])
                    ->name('create')
                    ->middleware('permission:view_categories');
                Route::post('/', [ColumnController::class, 'store'])
                    ->name('store')
                    ->middleware('permission:view_categories');
                Route::get('/{column}', [ColumnController::class, 'show'])
                    ->name('show')
                    ->middleware('permission:view_categories');
                Route::get('/{column}/edit', [ColumnController::class, 'edit'])
                    ->name('edit')
                    ->middleware('permission:view_categories');
                Route::put('/{column}', [ColumnController::class, 'update'])
                    ->name('update')
                    ->middleware('permission:view_categories');
                Route::delete('/{column}', [ColumnController::class, 'destroy'])
                    ->name('destroy')
                    ->middleware('permission:view_categories');
            });

            Route::middleware('permission:view_articles')->group(function () {
                // 文章管理
                Route::resource('articles', ArticleController::class);
            });

            Route::middleware('permission:view_tags')->group(function () {
                // 标签管理
                Route::resource('tags', TagController::class);
            });

            Route::middleware('permission:view_comments')->group(function () {
                // 评论管理
                Route::resource('comments', CommentController::class);
            });

            // 系统管理路由组
            Route::middleware('permission:view_users')->group(function () {
                // 用户管理
                Route::resource('users', UserController::class);
            });

            Route::middleware('permission:view_roles')->group(function () {
                // 角色管理
                Route::resource('roles', RoleController::class);
            });

            Route::middleware('permission:manage_settings')->group(function () {
                // 系统设置
                Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
                Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
            });

            // 图片上传路由
            Route::post('upload-image', [ImageController::class, 'upload'])->name('upload.image');
            Route::post('proxy-image', [ImageController::class, 'proxyImage'])->name('proxy.image');
        });

    // 前台路由
    Route::get('/', function () {
        return view('home');
    })->name('home');
});

Route::get('/test/index', "\App\Http\Controllers\TestController@index");
Route::get('/admin/index', "\App\Http\Controllers\admin\indexController@index");