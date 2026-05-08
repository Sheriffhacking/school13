<?php

namespace App\Http\Controllers;

use App\Models\TeacherSalary;
use App\Models\User;
use App\Repositories\FinancialRepository;
use App\Traits\SchoolSession;
use Illuminate\Http\Request;

class TeacherSalaryController extends Controller
{
    use SchoolSession;

    protected $financialRepository;

    public function __construct(FinancialRepository $financialRepository)
    {
        $this->middleware('auth');
        $this->financialRepository = $financialRepository;
    }

    /**
     * Store teacher salary
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'base_salary' => 'required|numeric|min:0.01',
            'salary_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $session_id = $this->getSchoolCurrentSession();

        // Calculate deductions
        $deductions = $this->financialRepository->calculateDeductions($validated['base_salary']);

        $salary = TeacherSalary::create([
            'teacher_id' => $validated['teacher_id'],
            'session_id' => $session_id,
            'base_salary' => $validated['base_salary'],
            'tax_deduction' => $deductions['tax'],
            'benefits_deduction' => $deductions['benefits'],
            'net_salary' => $deductions['net'],
            'salary_date' => $validated['salary_date'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return back()->with('status', 'Salary recorded successfully!');
    }

    /**
     * Delete salary record
     */
    public function delete($id)
    {
        $salary = TeacherSalary::findOrFail($id);

        $salary->delete();

        return back()->with('status', 'Salary record deleted!');
    }
}
