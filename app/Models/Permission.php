<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * 可批量赋值的属性
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'http_method',
        'http_path',
        'description',
    ];

    /**
     * 获取拥有此权限的所有角色
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permission')
            ->withTimestamps();
    }

    /**
     * 获取HTTP方法数组
     */
    public function getHttpMethodArray(): array
    {
        return array_filter(explode(',', $this->http_method));
    }

    /**
     * 获取HTTP路径数组
     */
    public function getHttpPathArray(): array
    {
        return array_filter(explode(',', $this->http_path));
    }
}
