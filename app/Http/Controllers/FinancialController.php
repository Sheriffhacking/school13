<?php

namespace App\Http\Controllers;

use App\Traits\SchoolSession;
use App\Repositories\FinancialRepository;

class FinancialController extends Controller
{
    use SchoolSession;

    protected $financialRepository;

    public function __construct(FinancialRepository $financialRepository)
    {
        $this->middleware('auth');
        $this->financialRepository = $financialRepository;
    }

    /**
     * Display financial dashboard
     */
    public function dashboard()
    {
        $session_id = $this->getSchoolCurrentSession();

        $summary = $this->financialRepository->getFinancialSummary($session_id);
        $expensesByCategory = $this->financialRepository->getExpensesByCategory($session_id);
        $recentExpenses = $this->financialRepository->getExpensesBySession($session_id)
            ->take(5);

        return view('finances.dashboard', [
            'summary' => $summary,
            'expensesByCategory' => $expensesByCategory,
            'recentExpenses' => $recentExpenses,
        ]);
    }

    /**
     * Display student fees page
     */
    public function studentFees()
    {
        $session_id = $this->getSchoolCurrentSession();
        $studentFees = $this->financialRepository->getStudentFeeStatus($session_id);

        return view('finances.student-fees', [
            'studentFees' => $studentFees,
            'summary' => $this->financialRepository->getFinancialSummary($session_id),
        ]);
    }

    /**
     * Display expenses page
     */
    public function expenses()
    {
        $session_id = $this->getSchoolCurrentSession();
        $expenses = $this->financialRepository->getExpensesBySession($session_id);
        $categories = \App\Models\ExpenseCategory::all();

        return view('finances.expenses', [
            'expenses' => $expenses,
            'categories' => $categories,
        ]);
    }

    /**
     * Display teacher salaries page
     */
    public function salaries()
    {
        $session_id = $this->getSchoolCurrentSession();
        $salaries = $this->financialRepository->getTeacherSalaries($session_id);

        return view('finances.salaries', [
            'salaries' => $salaries,
        ]);
    }
}
