<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('m_users_data', function (Blueprint $table) {
            $table->integer("id")->autoIncrement()->nullable(false);
            $table->timestamps();
            $table->string("user_name", 100)->nullable(false);
            $table->string("password", 100)->nullable(false);
            $table->tinyInteger("role")->default(1)->nullable(false);
            $table->boolean("del_flg")->default(0)->nullable();
            $table->integer("created_user")->nullable();
            $table->dateTime("created_datetime")->nullable();
            $table->integer("updated_user")->nullable();
            $table->dateTime("updated_datetime")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_user_data');
    }
};
