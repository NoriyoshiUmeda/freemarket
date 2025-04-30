<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCategoryIdTypeInItemsTable extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('category_id', 255)->change();
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->change();
        });
    }
}
