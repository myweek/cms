<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        try {
            $user = $request->user();

            // 记录权限检查信息
            Log::info('Permission check started', [
                'user' => $user ? [
                    'id' => $user->id,
                    'username' => $user->username,
                    'role' => $user->role,
                    'roles' => $user->roles()->pluck('slug')->toArray(),
                    'is_admin' => $user->isAdmin(),
                ] : 'guest',
                'permission' => $permission,
                'path' => $request->path(),
                'method' => $request->method(),
                'middleware' => $request->route()->middleware(),
                'route_name' => $request->route()->getName(),
            ]);

            // 如果用户未登录，返回403
            if (!$user) {
                Log::warning('User not authenticated');
                abort(403, '请先登录');
            }

            // 如果是管理员，直接通过
            if ($user->role === 'admin') {
                Log::info('Admin role detected, granting access', [
                    'user' => $user->username,
                    'role' => $user->role,
                ]);

                $response = $next($request);
                
                Log::info('Response after admin check', [
                    'status' => $response->status(),
                    'content' => method_exists($response, 'content') ? substr($response->content(), 0, 100) : 'No content method',
                ]);

                return $response;
            }

            // 检查用户是否有管理员角色
            if ($user->hasRole('admin')) {
                Log::info('Admin role relationship detected, granting access', [
                    'user' => $user->username,
                    'roles' => $user->roles()->pluck('slug')->toArray(),
                ]);

                $response = $next($request);
                
                Log::info('Response after admin role check', [
                    'status' => $response->status(),
                    'content' => method_exists($response, 'content') ? substr($response->content(), 0, 100) : 'No content method',
                ]);

                return $response;
            }

            // 检查用户是否有指定权限
            $hasPermission = $user->hasPermission($permission);
            
            // 记录详细的权限检查结果
            Log::info('Permission check details', [
                'user' => $user->username,
                'permission' => $permission,
                'has_permission' => $hasPermission,
                'roles' => $user->roles()->pluck('slug')->toArray(),
                'role_permissions' => $user->roles()
                    ->with('permissions')
                    ->get()
                    ->pluck('permissions')
                    ->flatten()
                    ->pluck('slug')
                    ->toArray(),
            ]);

            if (!$hasPermission) {
                Log::warning('Permission denied', [
                    'user' => $user->username,
                    'permission' => $permission,
                ]);
                abort(403, '您没有权限访问此页面');
            }

            Log::info('Permission granted', [
                'user' => $user->username,
                'permission' => $permission,
            ]);

            $response = $next($request);
            
            Log::info('Response after permission check', [
                'status' => $response->status(),
                'content' => method_exists($response, 'content') ? substr($response->content(), 0, 100) : 'No content method',
            ]);

            return $response;

        } catch (\Exception $e) {
            Log::error('Permission check error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            abort(403, '权限检查过程中发生错误');
        }
    }
}
