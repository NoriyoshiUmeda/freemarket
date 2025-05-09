<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
        public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // bigint unsigned, AUTO_INCREMENT, PRIMARY KEY
            $table->string('category', 255); // VARCHAR(255) NOT NULL
            $table->timestamps(); // created_at, updated_at 自動追加
        });
    }

        public function down()
    {
        Schema::dropIfExists('categories');
    }
}
