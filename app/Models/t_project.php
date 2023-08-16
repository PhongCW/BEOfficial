<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class t_project extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = "t_project_actual";
    protected $fillable = [
        'id',
        'project_id',
        'staff_id',
        'this_year_04_plan',
        'this_year_04_actual',
        'this_year_05_plan',
        'this_year_05_actual',
        'this_year_06_plan',
        'this_year_06_actual',
        'this_year_07_plan',
        'this_year_07_actual',
        "this_year_08_plan",
        "this_year_08_actual",
        "this_year_09_plan",
        "this_year_09_actual",
        "this_year_10_plan",
        "this_year_10_actual",
        "this_year_11_plan",
        "this_year_11_actual",
        "this_year_12_plan",
        "this_year_12_actual",
        "nextyear_01_plan",
        "nextyear_01_actual",
        "nextyear_02_plan",
        "nextyear_02_actual",
        "nextyear_03_plan",
        "nextyear_03_actual",
        "del_flg",
        "created_user",
        "created_datetime",
        "updated_user",
        "updated_datetime",
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
