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
        Schema::create('m_staffs_data', function (Blueprint $table) {
            $table->integer("id")->nullable(false)->autoIncrement();
            $table->timestamps();
            $table->string("last_name", 200)->nullable(false);
            $table->string("first_name", 200)->nullable(false);
            $table->string("last_name_furigana", 200)->nullable();
            $table->string("first_name_furigana", 200)->nullable();
            $table->tinyInteger("staff_type")->nullable(false);
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
        Schema::dropIfExists('m_staffs_data');
    }
};
