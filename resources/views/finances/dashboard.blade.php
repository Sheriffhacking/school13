@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-start">
        @include('layouts.left-menu')
        <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10">
            <div class="row pt-3">
                <div class="col ps-4">
                    <h1 class="display-6 mb-4"><i class="bi bi-graph-up me-2"></i>Financial Dashboard</h1>

                    <!-- Financial Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="card border-success">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-cash-coin me-2 text-success"></i>Total Income (Fees)</h5>
                                    <h3 class="text-success">${{ number_format($summary['total_fee_collected'], 2) }}</h3>
                                    <small class="text-muted">Due: ${{ number_format($summary['total_fee_due'], 2) }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card border-warning">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-exclamation-circle me-2 text-warning"></i>Outstanding Fees</h5>
                                    <h3 class="text-warning">${{ number_format($summary['total_fee_outstanding'], 2) }}</h3>
                                    <small class="text-muted">Not yet collected</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card border-danger">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-briefcase me-2 text-danger"></i>Total Expenses</h5>
                                    <h3 class="text-danger">${{ number_format($summary['total_expenses'], 2) }}</h3>
                                    <small class="text-muted">Salaries + Other</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-piggy-bank me-2 text-primary"></i>Net Benefit</h5>
                                    <h3 class="text-primary">${{ number_format($summary['net_benefit'], 2) }}</h3>
                                    <small class="text-muted">Income - Expenses</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Breakdown -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Expense Breakdown</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>Amount</th>
                                                <th>Percentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($expensesByCategory as $expense)
                                            <tr>
                                                <td>{{ $expense->category->name }}</td>
                                                <td>${{ number_format($expense->total, 2) }}</td>
                                                <td>{{ number_format(($expense->total / $summary['total_expenses'] * 100), 1) }}%</td>
                                            </tr>
                                            @endforeach
                                            <tr class="table-secondary">
                                                <th>Total Expenses</th>
                                                <th>${{ number_format($summary['total_expenses'], 2) }}</th>
                                                <th>100%</th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('finances.student-fees') }}" class="btn btn-outline-primary btn-lg w-100">
                                <i class="bi bi-receipt me-2"></i>Manage Student Fees
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('finances.expenses') }}" class="btn btn-outline-danger btn-lg w-100">
                                <i class="bi bi-cash-flow me-2"></i>Manage Expenses
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('finances.salaries') }}" class="btn btn-outline-info btn-lg w-100">
                                <i class="bi bi-people me-2"></i>Manage Salaries
                            </a>
                        </div>
                    </div>

                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>
</div>
@endsection
