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
        Schema::create('t_project_actual', function (Blueprint $table) {
            $table->id()->nullable();
            $table->timestamps();
            $table->integer("project_id");
            $table->integer("staff_id");
            $table->integer("this_year_04_plan");
            $table->integer("this_year_04_actual");
            $table->integer("this_year_05_plan");
            $table->integer("this_year_05_actual");
            $table->integer("this_year_06_plan");
            $table->integer("this_year_06_actual");
            $table->integer("this_year_07_plan");
            $table->integer("this_year_07_actual");
            $table->integer("this_year_08_plan");
            $table->integer("this_year_08_actual");
            $table->integer("this_year_09_plan");
            $table->integer("this_year_09_actual");
            $table->integer("this_year_10_plan");
            $table->integer("this_year_10_actual");
            $table->integer("this_year_11_plan");
            $table->integer("this_year_11_actual");
            $table->integer("this_year_12_plan");
            $table->integer("this_year_12_actual");
            $table->integer("nextyear_01_plan");
            $table->integer("nextyear_01_actual");
            $table->integer("nextyear_02_plan");
            $table->integer("nextyear_02_actual");
            $table->integer("nextyear_03_plan");
            $table->integer("nextyear_03_actual");
            $table->boolean("del_flg");
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
        Schema::dropIfExists('t_project_actual');
    }
};
