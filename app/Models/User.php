<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'name',
        'avatar',
        'role',
        'is_active',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    /**
     * 获取用户的所有角色
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role')
            ->withTimestamps();
    }

    /**
     * 检查用户是否具有指定角色
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()
            ->where('slug', $role)
            ->exists();
    }

    /**
     * 检查用户是否具有指定权限
     */
    public function hasPermission(string $permission): bool
    {
        // 如果是管理员，直接返回 true
        if ($this->role === 'admin') {
            return true;
        }

        // 如果有管理员角色，直接返回 true
        if ($this->hasRole('admin')) {
            return true;
        }

        // 检查用户角色是否有指定权限
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('slug', $permission);
            })
            ->exists();
    }

    /**
     * 获取用户的所有权限
     */
    public function getAllPermissions(): array
    {
        if ($this->role === 'admin' || $this->hasRole('admin')) {
            return Permission::all()
                ->map(function ($permission) {
                    return [
                        'http_method' => $permission->http_method,
                        'http_path' => $permission->http_path,
                    ];
                })
                ->toArray();
        }

        return $this->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->unique('id')
            ->map(function ($permission) {
                return [
                    'http_method' => $permission->http_method,
                    'http_path' => $permission->http_path,
                ];
            })
            ->toArray();
    }

    /**
     * 判断用户是否是管理员
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->hasRole('admin');
    }

    /**
     * 判断用户是否是编辑
     */
    public function isEditor(): bool
    {
        return $this->role === 'editor' || $this->hasRole('editor');
    }

    /**
     * 判断用户是否被禁用
     */
    public function isDisabled(): bool
    {
        return !$this->is_active;
    }

    /**
     * 实现 Laravel 的权限检查接口
     */
    public function can($abilities, $arguments = []): bool
    {
        // 如果是管理员，直接返回 true
        if ($this->isAdmin()) {
            return true;
        }

        if (is_array($abilities)) {
            foreach ($abilities as $ability) {
                if ($this->hasPermission($ability)) {
                    return true;
                }
            }
            return false;
        }

        return $this->hasPermission($abilities);
    }
}
