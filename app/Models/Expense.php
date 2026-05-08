<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'session_id',
        'category_id',
        'description',
        'amount',
        'expense_date',
        'notes',
        'status'
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function session()
    {
        return $this->belongsTo(SchoolSession::class);
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }
}
