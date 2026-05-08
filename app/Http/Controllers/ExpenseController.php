<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Traits\SchoolSession;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    use SchoolSession;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store new expense
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:expense_categories,id',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['session_id'] = $this->getSchoolCurrentSession();

        Expense::create($validated);

        return back()->with('status', 'Expense recorded successfully!');
    }

    /**
     * Delete expense
     */
    public function delete($id)
    {
        $expense = Expense::findOrFail($id);

        $expense->delete();

        return back()->with('status', 'Expense deleted successfully!');
    }
}
