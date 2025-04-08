<?php

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
        // 角色表
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique()->comment('角色名称');
            $table->string('slug', 50)->unique()->comment('角色标识');
            $table->string('display_name', 50)->comment('显示名称');
            $table->string('description')->nullable()->comment('角色描述');
            $table->boolean('is_active')->default(true)->comment('是否启用');
            $table->timestamps();
            $table->softDeletes();
        });

        // 权限表
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique()->comment('权限名称');
            $table->string('slug', 50)->unique()->comment('权限标识');
            $table->string('http_method')->nullable()->comment('HTTP方法');
            $table->string('http_path')->nullable()->comment('HTTP路径');
            $table->string('description')->nullable()->comment('权限描述');
            $table->timestamps();
            $table->softDeletes();
        });

        // 角色权限关联表
        Schema::create('role_permission', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->comment('角色ID');
            $table->unsignedBigInteger('permission_id')->comment('权限ID');
            $table->timestamps();

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');
            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->primary(['role_id', 'permission_id']);
        });

        // 用户角色关联表
        Schema::create('user_role', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->unsignedBigInteger('role_id')->comment('角色ID');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['user_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_role');
        Schema::dropIfExists('role_permission');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
