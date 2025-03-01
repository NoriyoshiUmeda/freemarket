<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); // bigint unsigned, AUTO_INCREMENT, PRIMARY KEY
            $table->foreignId('purchase_id')->constrained('purchases')->onDelete('cascade'); 
            $table->integer('amount'); // 支払金額 (例: 価格)
            $table->enum('payment_method', ['credit_card', 'convenience_store']);//支払方法enum
            $table->string('stripe_payment_id', 255)->unique(); // Stripe決済ID, UNIQUE制約
            $table->timestamps(); // created_at, updated_at 自動追加
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
