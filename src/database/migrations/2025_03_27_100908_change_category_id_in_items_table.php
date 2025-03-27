<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCategoryIdInItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('items', function (Blueprint $table) {
        $table->json('category_id')->change();
    });
}

public function down()
{
    Schema::table('items', function (Blueprint $table) {
        $table->unsignedBigInteger('category_id')->change();
    });
}

}
