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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("product_code", 50);
            $table->string("product_name", 200);
            $table->text("product_image");
            $table->string("slug", 100);
            $table->decimal("price_old", 9, 0);
            $table->decimal("price_new", 9, 0);
            $table->integer("qty_sold");
            $table->integer("qty_remain");
            $table->text("product_detail");
            $table->text("product_desc");
            $table->enum('product_status', ['pending', 'licensed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
