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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("p_order_id")->nullable();
            $table->unsignedBigInteger("p_user_id")->nullable();
            $table->decimal("p_money")->nullable()->comment("Số tiền thanh toán");
            $table->string("note", 200)->nullable()->comment("Nội dung thanh toán");
            $table->string("p_vnp_response_code", 255)->nullable()->comment("Mã phản hồi");
            $table->string("p_code_vnp", 255)->nullable()->comment("Mã giao dịch vnp");
            $table->string("p_code_bank", 255)->nullable()->comment("Mã ngân hàng");
            $table->dateTime("p_time")->nullable()->comment("Thời gian chuyển khoản");
            $table->foreign("p_order_id")->references("id")->on("orders")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
