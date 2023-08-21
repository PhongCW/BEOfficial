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

class T_project_Controller extends Controller
{
    public function indexApi(Request $request, $selectedProjectId)
    {
        try {
            // lấy project id ở bảng plan
            $projectInPlanActuals = DB::table('t_project_actual')
                ->where('project_id', $selectedProjectId)
                ->get();

            // lấy danh sách tất cả nhân viên từ m_staff_datas
            $allStaffs = DB::table('m_staffs_data')
                ->select('id as staff_id', 'staff_type', DB::raw("CONCAT(last_name, ' ', first_name) AS full_name"))
                ->get();

            // Lấy thông tin project từ bảng t_projects
            $projectData = DB::table('t_projects')
                ->where('id', $selectedProjectId)
                ->first();

            if (!$projectData) {
                return response()->json(['message' => 'Project not found'], 404);
            }

            if (count($projectInPlanActuals) > 0) {
                $results = [
                    'projectData' => $projectData,
                    'details' => []  // chi tiết về staff và dữ liệu t_project_plan_actuals
                ];

                $planActualStaffIds = [];
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
                $remainingStaffIds = $allStaffs->pluck('staff_id')->diff($planActualStaffIds);

                $remainingStaffs = [];
                foreach ($remainingStaffIds as $remainingStaffId) {
                    $staff = $allStaffs->firstWhere('staff_id', $remainingStaffId);
                    if ($staff) {
                        $remainingStaffs[] = $staff;  // thêm vào mảng riêng
                    }
                }

                $results['remainingStaffs'] = $remainingStaffs;

                return response()->json($results);
            } else {
                return response()->json(['projectData' => $projectData, 'staffData' => $allStaffs]);
            }
        } catch (\Exception $e) {
            Log::error("Error in PlantController@indexApi: " . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }


    public function saveProjectPlanActuals(Request $request)
    {
        
        try {
            $validatedData = $request->validate([
                'project_id' => 'required|integer|exists:t_projects,id',
                'staff_id' => 'required|array',
                'staff_id.*' => 'integer|exists:m_staffs_data,id',
                'this_year_04_plan' => 'nullable|numeric',
                'this_year_04_actual' => 'nullable|numeric',
                'this_year_05_plan' => 'nullable|numeric',
                'this_year_05_actual' => 'nullable|numeric',
                'this_year_06_plan' => 'nullable|numeric',
                'this_year_06_actual' => 'nullable|numeric',
                'this_year_07_plan' => 'nullable|numeric',
                'this_year_07_actual' => 'nullable|numeric',
                'this_year_08_plan' => 'nullable|numeric',
                'this_year_08_actual' => 'nullable|numeric',
                'this_year_09_plan' => 'nullable|numeric',
                'this_year_09_actual' => 'nullable|numeric',
                'this_year_10_plan' => 'nullable|numeric',
                'this_year_10_actual' => 'nullable|numeric',
                'this_year_11_plan' => 'nullable|numeric',
                'this_year_11_actual' => 'nullable|numeric',
                'this_year_12_plan' => 'nullable|numeric',
                'this_year_12_actual' => 'nullable|numeric',
                'nextyear_01_plan' => 'nullable|numeric',
                'nextyear_01_actual' => 'nullable|numeric',
                'nextyear_02_plan' => 'nullable|numeric',
                'nextyear_02_actual' => 'nullable|numeric',
                'nextyear_03_plan' => 'nullable|numeric',
                'nextyear_03_actual' => 'nullable|numeric',
            ]);

            $IDLoginUser = $request->IDLoginUser;

        foreach ($validatedData['staff_id'] as $staffId) {
            $dataToInsertOrUpdate = [
                'staff_id' => $staffId,
                'project_id' => $validatedData['project_id'],
                'updated_user' => $IDLoginUser,
                'updated_datetime' => now(),
            ];

            // Kiểm tra và cập nhật dữ liệu
            $existingRecord = DB::table('t_project_actual')
                ->where('project_id', $validatedData['project_id'])
                ->where('staff_id', $staffId)
                ->first();

            if (!$existingRecord) {
                $dataToInsertOrUpdate['created_user'] = $IDLoginUser;
                $dataToInsertOrUpdate['created_datetime'] = now();
            }

            foreach ($validatedData as $key => $value) {
                if (!is_null($value) && $key != 'staff_id') {
                    $dataToInsertOrUpdate[$key] = $value;
                }
            }

            if ($existingRecord) {
                DB::table('t_project_actual')
                    ->where('project_id', $validatedData['project_id'])
                    ->where('staff_id', $staffId)
                    ->update($dataToInsertOrUpdate);
            } else {
                DB::table('t_project_actual')->insert($dataToInsertOrUpdate);
            }
        }

        return response()->json(['message' => 'Data saved successfully']);
    } catch (\Exception $e) {
        Log::error("Error in PlantController@saveProjectPlanActuals: " . $e->getMessage());
        return response()->json(['message' => 'Internal Server Error'], 500);
    }

    }
}
