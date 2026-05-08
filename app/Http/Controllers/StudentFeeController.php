<?php

namespace App\Http\Controllers;

use App\Models\StudentFee;
use App\Traits\SchoolSession;
use Illuminate\Http\Request;

class StudentFeeController extends Controller
{
    use SchoolSession;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Record student fee payment
     */
    public function recordPayment(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'amount_paid' => 'required|numeric|min:0',
            'receipt_number' => 'nullable|string',
        ]);

        $session_id = $this->getSchoolCurrentSession();
        
        $studentFee = StudentFee::where('student_id', $validated['student_id'])
            ->where('session_id', $session_id)
            ->first();

        if (!$studentFee) {
            $studentFee = StudentFee::create([
                'student_id' => $validated['student_id'],
                'session_id' => $session_id,
                'amount_due' => 50, // Default fee
            ]);
        }

        $studentFee->amount_paid += $validated['amount_paid'];
        $studentFee->receipt_number = $validated['receipt_number'];
        $studentFee->payment_date = now();

        // Update status
        if ($studentFee->amount_paid >= $studentFee->amount_due) {
            $studentFee->status = 'paid';
        } elseif ($studentFee->amount_paid > 0) {
            $studentFee->status = 'partial';
        }

        $studentFee->save();

        return back()->with('status', 'Payment recorded successfully!');
    }
}
