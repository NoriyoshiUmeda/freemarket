<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavoritesTable extends Migration
{
        public function up()
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id(); // bigint unsigned, AUTO_INCREMENT, PRIMARY KEY
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->timestamps(); // created_at, updated_at 自動追加

            $table->unique(['user_id', 'item_id']); // 複数回のいいね制限
        });
    }

        public function down()
    {
        Schema::dropIfExists('favorites');
    }
}
