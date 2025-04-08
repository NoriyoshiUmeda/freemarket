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
        // 1. 既存データの変換：JSON_UNQUOTE() を使い、文字列から数値に変換する
        DB::statement("UPDATE items SET category_id = JSON_UNQUOTE(category_id) WHERE category_id IS NOT NULL");

        // 2. category_id カラムの型を unsignedBigInteger に変更
        Schema::table('items', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->change();
        });

        // 3. 外部キー制約を再構築する
        Schema::table('items', function (Blueprint $table) {
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
   public function down()
{
    // 外部キーの存在をチェックして削除（以前のコード例と同様）
    $foreignKeyExists = false;
    $result = DB::select("SHOW CREATE TABLE items");
    if (isset($result[0]->{'Create Table'})) {
        $createTable = $result[0]->{'Create Table'};
        if (strpos($createTable, 'items_category_id_foreign') !== false) {
            $foreignKeyExists = true;
        }
    }
    
    if ($foreignKeyExists) {
        try {
            DB::statement("ALTER TABLE items DROP FOREIGN KEY `items_category_id_foreign`");
        } catch (\Exception $e) {
            // 外部キーが存在しない場合は無視
        }
    }
    
    // インデックスが残っている可能性があるため DROP INDEX も試みる
    try {
        DB::statement("ALTER TABLE items DROP INDEX `items_category_id_foreign`");
    } catch (\Exception $e) {
        // インデックスが存在しない場合は無視
    }

    // ↓ カラム内の値を、正しい JSON 形式に更新する
    // 例: "1,2,3" → "[1,2,3]"
    DB::statement("UPDATE items SET category_id = CONCAT('[', category_id, ']') WHERE category_id IS NOT NULL");

    // カラム型を JSON 型に戻す
    Schema::table('items', function (Blueprint $table) {
        $table->json('category_id')->change();
    });
}

}