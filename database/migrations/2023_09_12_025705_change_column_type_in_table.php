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
        Schema::table('t_project_plan_actuals', function (Blueprint $table) {
            $table->float("this_year_04_plan")->change();
            $table->float("this_year_04_actual")->change();
            $table->float("this_year_05_plan")->change();
            $table->float("this_year_05_actual")->change();
            $table->float("this_year_06_plan")->change();
            $table->float("this_year_06_actual")->change();
            $table->float("this_year_07_plan")->change();
            $table->float("this_year_07_actual")->change();
            $table->float("this_year_08_plan")->change();
            $table->float("this_year_08_actual")->change();
            $table->float("this_year_09_plan")->change();
            $table->float("this_year_09_actual")->change();
            $table->float("this_year_10_plan")->change();
            $table->float("this_year_10_actual")->change();
            $table->float("this_year_11_plan")->change();
            $table->float("this_year_11_actual")->change();
            $table->float("this_year_12_plan")->change();
            $table->float("this_year_12_actual")->change();
            $table->float("nextyear_01_plan")->change();
            $table->float("nextyear_01_actual")->change();
            $table->float("nextyear_02_plan")->change();
            $table->float("nextyear_02_actual")->change();
            $table->float("nextyear_03_plan")->change();
            $table->float("nextyear_03_actual")->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('table', function (Blueprint $table) {
            //
        });
    }
};
