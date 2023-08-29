<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class t_project_actual_plan extends Model
{
    use HasFactory;
    protected $table = "t_project_plan_actuals";
    public $timestamps = true;
}
