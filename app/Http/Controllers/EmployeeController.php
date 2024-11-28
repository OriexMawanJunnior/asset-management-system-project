<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmployeeController 
{
    /**
     * Display a listing of the employees.
     */
    public function index()
    {
        try {
            $employees = Employee::paginate(10);
            return view('page.user.index', compact('employees'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to load employees. Please try again.');
        }
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        return view('page.user.create');
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate($this->validationRules());
            
            $employee = Employee::create($validatedData);
            
            return redirect()->route('users.index')
                ->with('message', 'Employee created successfully');
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (Exception $e) {
            return back()
                ->with('error', 'Failed to create employee. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified employee.
     */
    public function show(string $id)
    {
        try {
            $employee = Employee::findOrFail($id);
            return view('page.user.show', compact('employee'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('users.index')
                ->with('error', 'Employee not found.');
        } catch (Exception $e) {
            return back()->with('error', 'An error occurred while fetching employee details.');
        }
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(string $id)
    {
        try {
            $employee = Employee::findOrFail($id);
            return view('page.user.edit', compact('employee'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('users.index')
                ->with('error', 'Employee not found.');
        } catch (Exception $e) {
            return back()->with('error', 'An error occurred while preparing employee edit.');
        }
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $employee = Employee::findOrFail($id);
            
            $validatedData = $request->validate($this->validationRules());
            
            $employee->update($validatedData);
            
            return redirect()->route('users.index')
                ->with('message', 'Employee updated successfully');
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('users.index')
                ->with('error', 'Employee not found.');
        } catch (Exception $e) {
            return back()
                ->with('error', 'Failed to update employee. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy(string $id)
    {
        try {
            $employee = Employee::findOrFail($id);
            $employee->delete();
            
            return redirect()->route('users.index')
                ->with('message', 'Employee deleted successfully');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('users.index')
                ->with('error', 'Employee not found.');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to delete employee. Please try again.');
        }
    }

    /**
     * Validation rules for storing and updating employees.
     */
    private function validationRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'job_position' => 'required|string|max:255',
        ];
    }
}