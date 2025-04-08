<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200)->comment('文章标题');
            $table->string('slug', 200)->unique()->comment('文章别名');
            $table->text('content')->comment('文章内容');
            $table->text('excerpt')->nullable()->comment('文章摘要');
            $table->string('cover_image')->nullable()->comment('封面图片');
            $table->integer('category_id')->comment('分类ID');
            $table->integer('author_id')->comment('作者ID');
            $table->integer('view_count')->default(0)->comment('浏览次数');
            $table->integer('comment_count')->default(0)->comment('评论数量');
            $table->boolean('is_recommended')->default(false)->comment('是否推荐');
            $table->boolean('is_top')->default(false)->comment('是否置顶');
            $table->timestamp('published_at')->nullable()->comment('发布时间');
            $table->enum('status', ['draft', 'pending', 'published', 'rejected'])->default('draft')->comment('文章状态');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
}; 