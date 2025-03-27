<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id(); // bigint unsigned, AUTO_INCREMENT, PRIMARY KEY
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->json('category_id')->constrained('categories')->onDelete('cascade'); 
            $table->foreignId('status_id')->constrained('statuses')->onDelete('cascade'); 
            $table->string('name', 255); // VARCHAR(255) NOT NULL
            $table->string('description', 255); // VARCHAR(255) NOT NULL
            $table->integer('price'); // INT NOT NULL
            $table->string('image', 255);// VARCHAR(255) NOT NULL
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
        Schema::dropIfExists('items');
    }
}
