<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id(); // bigint unsigned, AUTO_INCREMENT, PRIMARY KEY
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('profile_image', 255)->nullable(); // VARCHAR(255), NULL許容
            $table->string('postal_code', 255); // VARCHAR(255) NOT NULL
            $table->string('address', 255); // VARCHAR(255) NOT NULL
            $table->string('building', 255)->nullable(); // VARCHAR(255) NULL許容
            $table->timestamp('email_verified_at')->nullable(); // timestamp, NULL許容
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
        Schema::dropIfExists('profiles');
    }
}
