<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        "/Login",
        '/Delete',
        '/Staff_Create',
        '/Staff_Detail_Edit',
        'HandleSearchStaff',
        '/Show_order_list',
        '/Delete_order',
        '/GetOrderByID',
        '/Actual_Plan',
        '/Order_Create',
        '/HandleSearchOrder',
        '/Order_Edit_Detail',
        '/GetStaffID',
        '/plant/{selected_project_id}',
        '/project-plan-actuals/save',
    ];
}
