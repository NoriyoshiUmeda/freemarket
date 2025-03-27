<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RevertCategoryIdTypeInItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. 既存データの変換： JSON_UNQUOTE() を使い、文字列から数値に変換する
        DB::statement("UPDATE items SET category_id = JSON_UNQUOTE(category_id) WHERE category_id IS NOT NULL");

        // 2. category_id カラムの型を unsignedBigInteger に変更
        Schema::table('items', function (Blueprint $table) {
            // 外部キー制約やインデックスは一旦削除済みと仮定
            $table->unsignedBigInteger('category_id')->change();
        });

        // 3. 外部キー制約を再構築する
        Schema::table('items', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // down() メソッドでは、元の状態（複数カテゴリー対応のための JSON 型）に戻す処理を実装できますが、
        // ここでは単純に外部キー制約を削除し、カラム型を JSON に戻す例を示します。

        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });

        Schema::table('items', function (Blueprint $table) {
            // JSON型に戻す（MySQL が JSON 型をサポートしている場合）
            $table->json('category_id')->change();
        });
    }
}
