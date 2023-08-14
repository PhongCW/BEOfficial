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
        Schema::create('m_user_data', function (Blueprint $table) {
            $table->integer("id")->nullable()->autoIncrement();
            $table->timestamps();
            $table->string("user_name", 100)->nullable();
            $table->string("password", 100)->nullable();
            $table->tinyInteger("role")->default(1)->nullable();
            $table->boolean("del_flg")->default(0)->nullable();
            $table->integer("created_user");
            $table->dateTime("created_datetime");
            $table->integer("updated_user");
            $table->dateTime("updated_datetime");
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
