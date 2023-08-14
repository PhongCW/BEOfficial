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
        Schema::create('t_projects', function (Blueprint $table) {
            $table->id()->nullable()->autoIncrement();
            $table->timestamps();
            $table->string('project_name', 100);
            $table->string('order_number', 100)->nullable();
            $table->string("client_name", 100);
            $table->dateTime("order_date");
            $table->tinyInteger("status")->default(0);
            $table->integer("order_income");
            $table->integer("internal_unit_price");
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
        Schema::dropIfExists('t_projects');
    }
};
