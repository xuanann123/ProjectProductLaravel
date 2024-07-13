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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string("post_title", 300);
            $table->string("post_image", 300);
            $table->text("post_description");
            $table->text("post_content");
            $table->string("slug", 100);
            $table->enum('post_status', ['Hoạt động', 'Chưa phê duyệt'])->default('Chưa phê duyệt');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
