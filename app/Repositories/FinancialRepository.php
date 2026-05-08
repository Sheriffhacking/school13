<?php

namespace App\Repositories;

use App\Models\StudentFee;
use App\Models\Expense;
use App\Models\TeacherSalary;
use App\Models\SalaryComponent;

class FinancialRepository
{
    /**
     * Get financial summary for current session
     */
    public function getFinancialSummary($session_id)
    {
        $totalFeeDue = StudentFee::where('session_id', $session_id)
            ->sum('amount_due');

        $totalFeeCollected = StudentFee::where('session_id', $session_id)
            ->sum('amount_paid');

        $totalFeeOutstanding = $totalFeeDue - $totalFeeCollected;

        $totalExpenses = Expense::where('session_id', $session_id)
            ->sum('amount');

        $totalSalaries = TeacherSalary::where('session_id', $session_id)
            ->sum('net_salary');

        $totalBenefits = TeacherSalary::where('session_id', $session_id)
            ->sum('benefits_deduction');

        return [
            'total_fee_due' => $totalFeeDue,
            'total_fee_collected' => $totalFeeCollected,
            'total_fee_outstanding' => $totalFeeOutstanding,
            'total_expenses' => $totalExpenses,
            'total_salaries' => $totalSalaries,
            'total_benefits' => $totalBenefits,
            'net_benefit' => $totalFeeCollected - $totalExpenses - $totalSalaries,
        ];
    }

    /**
     * Get student fee status
     */
    public function getStudentFeeStatus($session_id)
    {
        return StudentFee::where('session_id', $session_id)
            ->with('student')
            ->get()
            ->map(function ($fee) {
                $fee->outstanding = $fee->amount_due - $fee->amount_paid;
                return $fee;
            });
    }

    /**
     * Get expenses by session
     */
    public function getExpensesBySession($session_id)
    {
        return Expense::where('session_id', $session_id)
            ->with('category')
            ->orderBy('expense_date', 'desc')
            ->get();
    }

    /**
     * Get expenses summary by category
     */
    public function getExpensesByCategory($session_id)
    {
        return Expense::where('session_id', $session_id)
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->with('category')
            ->get();
    }

    /**
     * Get teacher salaries
     */
    public function getTeacherSalaries($session_id)
    {
        return TeacherSalary::where('session_id', $session_id)
            ->with('teacher')
            ->orderBy('salary_date', 'desc')
            ->get();
    }

    /**
     * Calculate tax and benefits
     */
    public function calculateDeductions($base_salary)
    {
        $components = SalaryComponent::where('is_active', true)->get();

        $tax = 0;
        $benefits = 0;

        foreach ($components as $component) {
            $amount = $component->type === 'percentage' 
                ? ($base_salary * $component->rate / 100)
                : $component->rate;

            if ($component->deduction_type === 'tax') {
                $tax += $amount;
            } else {
                $benefits += $amount;
            }
        }

        return [
            'tax' => $tax,
            'benefits' => $benefits,
            'net' => $base_salary - $tax - $benefits
        ];
    }
}
