<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Employee::with(['user', 'workSchedule']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:user,id'],
            'name' => ['required', 'string'],
            'cpf' => ['required', 'string'],
            'registration_number' => ['required'],
            'hired_at' => ['required', 'date'],
            'active' => ['required', 'boolean'],
            'work_schedule_id' => ['required', 'exists:work_schedules,id'],
            'position' => ['required', 'string'],
        ]);

        $employee = Employee::create($validated);

        return response()->json($employee, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return response()->json($employee->load(['user', 'workSchedule']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string'],
            'cpf' => ['sometimes', 'string'],
            'registration_number' => ['sometimes'],
            'hired_at' => ['sometimes', 'date'],
            'active' => ['sometimes', 'boolean'],
            'work_schedule_id' => ['sometimes', 'exists:work_schedules,id'],
            'position' => ['sometimes', 'string'],
        ]);

        $employee->update($validated);

        return response()->json($employee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return response()->noContent();
    }
}
