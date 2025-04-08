<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Column extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * 指定表名
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * 可批量赋值的属性
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'order',
        'is_active',
    ];

    /**
     * 应该被转换成原生类型的属性
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'parent_id' => 'integer',
        'order' => 'integer',
    ];

    /**
     * 获取该栏目下的所有文章
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'category_id');
    }

    /**
     * 获取父级栏目
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * 获取子栏目
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
} 