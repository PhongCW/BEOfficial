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
            $table->id()->nullable(false)->autoIncrement();
            $table->timestamps();
            $table->string('project_name', 200)->nullable();
            $table->string('order_number', 100)->nullable(false);
            $table->string("client_name", 100)->nullable();
            $table->date("order_date")->nullable();
            $table->tinyInteger("status")->nullable();
            $table->integer("order_income")->nullable();
            $table->integer("internal_unit_price")->nullable();
            $table->boolean("del_flg")->nullable();
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
        Schema::dropIfExists('t_project');
    }
};
