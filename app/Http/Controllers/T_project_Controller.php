<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\t_project_actual_plan;
use App\Models\t_project;
use App\Models\Order;
use App\Models\Staff;
use App\Models\StaffModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class T_project_Controller extends Controller
{
    public function indexApi(Request $request)
    {

            $IDLoginUser = $request->IDLoginUser;
            // $IDLoginUser = session("IDLoginUser");
            $ID_Project = $request->selectedProjectId;
            $User = User::where("id", $IDLoginUser)->first();

            if (isset($User)){
                // lấy project id ở bảng plan
                Auth::login($User);
                $projectInPlanActuals = DB::table('t_project_plan_actuals')
                ->where('project_id', $ID_Project)
                ->get();

        // lấy danh sách tất cả nhân viên từ m_detailss
                $allStaffs = DB::table('m_staff_datas')
                    ->select('id as staff_id', 'staff_type', DB::raw("CONCAT(last_name, ' ', first_name) AS full_name"))
                    ->get();

                // Lấy thông tin project từ bảng t_projects
                $projectData = DB::table('t_projects')
                    ->where('id', $ID_Project)
                    ->first();

                if (!$projectData) {
                    return response()->json(['message' => 'Project not found'], 404);
                }

                if (count($projectInPlanActuals) > 0) {
                    $results = [
                        'projectData' => $projectData,
                        'details' => []  // chi tiết về staff và dữ liệu t_project_plan_actuals
                    ];

                    // $planActualStaffIds = [];
                    foreach ($projectInPlanActuals as $planActual) {
                        $planActualStaffIds[] = $planActual->staff_id;

                        $staff = $allStaffs->firstWhere('staff_id', $planActual->staff_id);
                        if ($staff) {
                            $results['details'][] = [
                                'planActualData' => $planActual,
                                'staffData' => $staff
                            ];
                        }
                    }

                    // show danh sách staff chưa có trong bảng plant
                    // $remainingStaffIds = $allStaffs->pluck('staff_id')->diff($planActualStaffIds);

                    // $remainingStaffs = [];
                    // foreach ($remainingStaffIds as $remainingStaffId) {
                    //     $staff = $allStaffs->firstWhere('staff_id', $remainingStaffId);
                    //     if ($staff) {
                    //         $remainingStaffs[] = $staff;  // thêm vào mảng riêng
                    //     }
                    // }

                    // $results['remainingStaffs'] = $remainingStaffs;

                    return response()->json([
                        "result"=>$results,
                        "remainingStaffs"=>$allStaffs
                    ]);
                } 
                else {
                    return response()->json(['projectData' => $projectData, "remainingStaffs"=>$allStaffs]);
                }
            }
            else{
                return response()->json([
                    "message"=>"You haven't login yet"
                ]);
        }
    }

    public function saveProjectPlanActuals(Request $request)
    {
        $IDLoginUser = $request->IDLoginUser;
        // $IDLoginUser = session("IDLoginUser");
        $User = User::where("id", $IDLoginUser)->first();

        if (isset($User)) {
            Auth::login($User);
            try {
                $validatedData = $request->validate([
                    'projectData.id' => 'required|integer|exists:t_projects,id',
                    'details' => 'required|array',
                    'staff_data.*.planActualData.staff_id' => 'required|integer|exists:m_staffs_data,id',
                    'staff_data.*.planActualData.this_year_04_plan' => 'nullable|numeric',
                    'staff_data.*.planActualData.this_year_04_actual' => 'nullable|numeric',
                    'staff_data.*.planActualData.this_year_05_plan' => 'nullable|numeric',
                    'staff_data.*.planActualData.this_year_05_actual' => 'nullable|numeric',
                    'staff_data.*.planActualData.this_year_06_plan' => 'nullable|numeric',
                    'staff_data.*.planActualData.this_year_06_actual' => 'nullable|numeric',
                    'staff_data.*.planActualData.this_year_07_plan' => 'nullable|numeric',
                    'staff_data.*.planActualData.this_year_07_actual' => 'nullable|numeric',
                    'staff_data.*.planActualData.this_year_08_plan' => 'nullable|numeric',
                    'staff_data.*.planActualData.this_year_08_actual' => 'nullable|numeric',
                    'staff_data.*.planActualData.this_year_09_plan' => 'nullable|numeric',
                    'staff_data.*.planActualData.this_year_09_actual' => 'nullable|numeric',
                    'staff_data.*.planActualData.this_year_10_plan' => 'nullable|numeric',
                    'staff_data.*.planActualData.this_year_10_actual' => 'nullable|numeric',
                    'staff_data.*.planActualData.this_year_11_plan' => 'nullable|numeric',
                    'staff_data.*.planActualData.this_year_11_actual' => 'nullable|numeric',
                    'staff_data.*.planActualData.this_year_12_plan' => 'nullable|numeric',
                    'staff_data.*.planActualData.this_year_12_actual' => 'nullable|numeric',
                    'staff_data.*.planActualData.nextyear_01_plan' => 'nullable|numeric',
                    'staff_data.*.planActualData.nextyear_01_actual' => 'nullable|numeric',
                    'staff_data.*.planActualData.nextyear_02_plan' => 'nullable|numeric',
                    'staff_data.*.planActualData.nextyear_02_actual' => 'nullable|numeric',
                    'staff_data.*.planActualData.nextyear_03_plan' => 'nullable|numeric',
                    'staff_data.*.planActualData.nextyear_03_actual' => 'nullable|numeric',
                ]);

                foreach ($validatedData['details'] as $staffDataWrapper) {
                    $planActualData = $staffDataWrapper['planActualData'];

                    $staffId = $planActualData['staff_id'];
                    $dataToInsertOrUpdate = [
                        'staff_id' => $staffId,
                        'project_id' => $validatedData['projectData']['id'],
                        'updated_user' => Auth::user()->id,
                        'del_flg'=>0,
                        'updated_datetime' => now(),
                    ];

                    $existingRecord = DB::table('t_project_plan_actuals')
                        ->where('project_id', $validatedData['projectData']['id'])
                        ->where('staff_id', $staffId)
                        ->first();

                    if (!$existingRecord) {
                        $dataToInsertOrUpdate['created_user'] = Auth::user()->id;
                        $dataToInsertOrUpdate['created_datetime'] = now();
                    }

                    $defaultFields = [
                        'this_year_04_plan', 'this_year_04_actual', 'this_year_05_plan', 'this_year_05_actual',
                        'this_year_06_plan', 'this_year_06_actual', 'this_year_07_plan', 'this_year_07_actual',
                        'this_year_09_plan', 'this_year_09_actual', 'this_year_08_plan', 'this_year_08_actual',
                        'this_year_10_plan', 'this_year_10_actual', 'this_year_11_plan', 'this_year_11_actual',
                        'this_year_12_plan', 'this_year_12_actual', 'nextyear_01_plan', 'nextyear_01_actual',
                        'nextyear_02_plan', 'nextyear_02_actual',
                        'nextyear_03_plan', 'nextyear_03_actual',
                    ];
                    foreach ($defaultFields as $field) {
                        if (!isset($planActualData[$field])) {
                            $dataToInsertOrUpdate[$field] = 0;
                        } else {
                            $dataToInsertOrUpdate[$field] = $planActualData[$field];
                        }
                    }

                    if ($existingRecord) {
                        DB::table('t_project_plan_actuals')
                            ->where('project_id', $validatedData['projectData']['id'])
                            ->where('staff_id', $staffId)
                            ->update($dataToInsertOrUpdate);
                    } else {
                        DB::table('t_project_plan_actuals')->insert($dataToInsertOrUpdate);
                    }
                }

                return response()->json(['message' => 'Data saved successfully']);
            } catch (\Exception $e) {
                Log::error("Error in PlantController@saveProjectPlanActuals: " . $e->getMessage());
                return response()->json(['message' => 'Internal Server Error'], 500);
            }
        } else {
            return response()->json([
                "message" => "You haven't Login yet"
            ], 404);
        }
    }
}
