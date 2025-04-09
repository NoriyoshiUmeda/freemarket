<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressDetailsToPurchasesTable extends Migration
{
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            // ユーザーIDの後に必須の郵便番号（varchar(255)）を追加
            $table->string('postal_code')->after('item_id');
            // addressカラムの後に建物情報（nullable、varchar(255)）を追加
            $table->string('building')->nullable()->after('address');
        });
    }

    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('postal_code');
            $table->dropColumn('building');
        });
    }
}
