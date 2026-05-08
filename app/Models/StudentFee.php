<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentFee extends Model
{
    protected $fillable = [
        'student_id',
        'session_id',
        'amount_due',
        'amount_paid',
        'status',
        'payment_date',
        'receipt_number',
        'notes'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount_due' => 'decimal:2',
        'amount_paid' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function session()
    {
        return $this->belongsTo(SchoolSession::class);
    }

    public function getOutstandingAttribute()
    {
        return $this->amount_due - $this->amount_paid;
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isPartiallyPaid()
    {
        return $this->status === 'partial';
    }
}
