<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherSalary extends Model
{
    protected $fillable = [
        'teacher_id',
        'session_id',
        'base_salary',
        'tax_deduction',
        'benefits_deduction',
        'net_salary',
        'salary_date',
        'status',
        'notes'
    ];

    protected $casts = [
        'salary_date' => 'date',
        'base_salary' => 'decimal:2',
        'tax_deduction' => 'decimal:2',
        'benefits_deduction' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function session()
    {
        return $this->belongsTo(SchoolSession::class);
    }

    public function calculateNetSalary()
    {
        return $this->base_salary - $this->tax_deduction - $this->benefits_deduction;
    }
}
