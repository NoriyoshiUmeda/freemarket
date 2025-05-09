<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusesTable extends Migration
{
        public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id(); // bigint unsigned, AUTO_INCREMENT, PRIMARY KEY
            $table->string('name', 255)->unique(); // VARCHAR(255) NOT NULL, UNIQUE制約
            $table->timestamps(); // created_at, updated_at 自動追加
        });
    }

        public function down()
    {
        Schema::dropIfExists('statuses');
    }
}
