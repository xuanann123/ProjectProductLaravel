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
        Schema::create('post_cat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("cat_id");
            $table->unsignedBigInteger("post_id");
            $table->foreign("cat_id")->references("id")->on("cats")->onDelete('cascade');
            $table->foreign("post_id")->references("id")->on("posts")->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_cat');
    }
};
