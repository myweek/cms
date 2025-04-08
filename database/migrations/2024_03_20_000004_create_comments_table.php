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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->integer('article_id')->comment('文章ID');
            $table->integer('user_id')->comment('用户ID');
            $table->integer('parent_id')->default(0)->comment('父评论ID');
            $table->text('content')->comment('评论内容');
            $table->boolean('is_approved')->default(false)->comment('是否审核通过');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
}; 