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
        Schema::create('m_staff_data', function (Blueprint $table) {
            $table->integer("id")->nullable()->autoIncrement();
            $table->timestamps();
            $table->string("last_name", 200)->nullable();
            $table->string("first_name", 200)->nullable();
            $table->string("last_name_furigana", 200);
            $table->string("first_name_furigana", 200);
            $table->string("office")->nullable();
            $table->tinyInteger("staff_type")->default(0)->nullable();
            $table->boolean("del_flg")->default(0);
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
        Schema::dropIfExists('m_staff_data');
    }
};
