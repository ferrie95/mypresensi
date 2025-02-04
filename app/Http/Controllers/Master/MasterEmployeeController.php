<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;


class MasterEmployeeController extends Controller
{
    public function index()
    {
        return view('master.employee.index');
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nik' => 'required|string|unique:employees,nik|max:20',
            'name' => 'required|string|max:100',
            'work_role' => 'nullable|integer',
            'kantor_cabang' => 'nullable|integer',
            'gender' => 'nullable|string|in:M,F',
            'department' => 'nullable|integer',
            'rank' => 'nullable|integer',
            'date_birth' => 'nullable|date',
            'region' => 'nullable|string|max:20',
            'phone_number' => 'nullable|string|max:20',
            'active' => 'nullable|boolean',
        ]);
    
        // Set password default
        $password = 'Mojokerto123';
    
        try {
            // Mulai transaksi
            DB::beginTransaction();
    
            // Buat Employee baru
            $employee = Employee::create([
                'nik' => $validatedData['nik'],
                'name' => $validatedData['name'],
                'password' => Hash::make($password),
                'work_role' => $validatedData['work_role'] ?? null,
                'kantor_cabang' => $validatedData['kantor_cabang'] ?? null,
                'gender' => $validatedData['gender'] ?? null,
                'department' => $validatedData['department'] ?? null,
                'rank' => $validatedData['rank'] ?? null,
                'date_birth' => $validatedData['date_birth'] ?? null,
                'region' => $validatedData['region'] ?? null,
                'phone_number' => $validatedData['phone_number'] ?? null,
                'active' => $validatedData['active'] ?? 1,
            ]);
    
            // Commit transaksi jika berhasil
            DB::commit();
    
            // Jika request dari API, kirim response JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Employee created successfully.',
                    'data' => $employee
                ], 201);
            }
    
            // Jika dari web, redirect dengan pesan sukses
            return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();
    
            // Log error
            Log::error('Error creating employee: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
    
            // Jika request dari API, kirim response JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create employee.',
                    'error' => $e->getMessage()
                ], 500);
            }
    
            // Jika dari web, redirect dengan pesan error
            return redirect()->route('employees.index')->with('error', 'Failed to create employee.');
        }
    }
    

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|max:100',
        ]);

        $employee->update([
            'name' => $request->name,
            'work_role' => $request->work_role,
            'kantor_cabang' => $request->kantor_cabang,
            'gender' => $request->gender,
            'department' => $request->department,
            'rank' => $request->rank,
            'date_birth' => $request->date_birth,
            'region' => $request->region,
            'phone_number' => $request->phone_number,
            'active' => $request->active,
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
