<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
        public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id(); // bigint unsigned, AUTO_INCREMENT, PRIMARY KEY
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->string('address', 255); // VARCHAR(255) NOT NULL
            $table->timestamps(); // created_at, updated_at 自動追加
        });
    }

        public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
