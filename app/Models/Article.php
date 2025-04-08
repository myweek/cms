<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * 可批量赋值的属性
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'cover_image',
        'category_id',
        'author_id',
        'view_count',
        'comment_count',
        'is_recommended',
        'is_top',
        'published_at',
        'status',
    ];

    /**
     * 应该被转换成原生类型的属性
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_recommended' => 'boolean',
        'is_top' => 'boolean',
        'published_at' => 'datetime',
        'view_count' => 'integer',
        'comment_count' => 'integer',
        'category_id' => 'integer',
        'author_id' => 'integer',
    ];

    /**
     * 获取文章是否已发布的属性
     */
    public function getIsPublishedAttribute(): bool
    {
        return $this->status === 'published' && $this->published_at !== null;
    }

    /**
     * 获取文章所属栏目
     */
    public function column(): BelongsTo
    {
        return $this->belongsTo(Column::class, 'category_id');
    }

    /**
     * 获取文章所属分类（向后兼容）
     */
    public function category(): BelongsTo
    {
        return $this->column();
    }

    /**
     * 获取文章作者
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * 获取文章标签
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * 获取文章评论
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}