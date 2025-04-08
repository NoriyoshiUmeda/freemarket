<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeCategoryIdTypeInItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 一時的に外部キー制約チェックを無効化
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 明示的に外部キー制約を削除
        DB::statement('ALTER TABLE items DROP FOREIGN KEY `items_category_id_foreign`;');

        // category_id カラムの型を VARCHAR(255) に変更
        Schema::table('items', function (Blueprint $table) {
            $table->string('category_id', 255)->change();
        });

        // 外部キーチェックを再有効化
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // down() で整数型に戻す場合（※外部キー制約が必要なら再構築も検討してください）
        Schema::table('items', function (Blueprint $table) {
            $table->integer('category_id')->change();
        });
    }
}
