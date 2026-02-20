<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Company;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::with('company')->latest()->paginate(10);
        $companies = Company::select('id','name')->get();

        return Inertia::render('Employees/Index', [
            'employees' => $employees,
            'companies' => $companies
        ]);
    }

    public function store(StoreEmployeeRequest $request)
    {
        Employee::create($request->validated());

        return redirect()->back()->with('success', 'Employee created successfully');
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $employee->update($request->validated());

        return redirect()->back()->with('success', 'Employee updated successfully');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->back()->with('success', 'Employee deleted successfully');
    }
}
