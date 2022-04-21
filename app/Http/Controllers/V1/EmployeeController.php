<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Employee;
use App\Http\Requests\V1\StoreEmployeeRequest;
use App\Http\Requests\V1\UpdateEmployeeRequest;
use App\Http\Resources\V1\EmployeeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $companyId
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function index(int $companyId): JsonResource
    {
        return EmployeeResource::collection(Employee::all()->where('company_id', $companyId));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $companyId
     * @param  \App\Http\Requests\StoreEmployeeRequest  $request
     * @return \App\Http\Resources\V1\EmployeeResource
     */
    public function store(int $companyId, StoreEmployeeRequest $request): EmployeeResource
    {
		$company = Company::where('id', $companyId)->first();
		if (!$company) {
			abort(404);
		}
		
		$employeeData = $request->all();
		$employeeData['company_id'] = $companyId;
        $employee = Employee::create($employeeData);
		
		return new EmployeeResource($employee);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $companyId
     * @param  int  $employeeId
     * @return \App\Http\Resources\V1\EmployeeResource
     */
    public function show(int $companyId, int $employeeId): EmployeeResource
    {
		$employee = Employee::where(['company_id' => $companyId, 'id' => $employeeId])->first();
		
		if (!$employee) {
			abort(404);
		}
		
        return new EmployeeResource($employee);
    }

    /**
     * Update the specified resource in storage.
     *
	 * @param  int  $companyId
     * @param  int  $employeeId
     * @param  \App\Http\Requests\UpdateEmployeeRequest  $request
     * @return \App\Http\Resources\V1\EmployeeResource
     */
    public function update(int $companyId, int $employeeId, UpdateEmployeeRequest $request): EmployeeResource
    {
		$employee = Employee::where(['company_id' => $companyId, 'id' => $employeeId])->first();
		
		if (!$employee) {
			abort(404);
		}
		
        $employee->update($request->all());
		
		return new EmployeeResource($employee);
    }

    /**
     * Remove the specified resource from storage.
     *
	 * @param  int  $companyId
     * @param  int  $employeeId
     * @return void
     */
    public function destroy(int $companyId, int $employeeId): void
    {
		$employee = Employee::where(['company_id' => $companyId, 'id' => $employeeId])->first();
		
		if (!$employee) {
			abort(404);
		}
		
        $employee->delete();
    }
}
