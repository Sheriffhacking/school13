@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-start">
        @include('layouts.left-menu')
        <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10">
            <div class="row pt-3">
                <div class="col ps-4">
                    <h1 class="display-6 mb-4"><i class="bi bi-receipt me-2"></i>Student Fee Management</h1>

                    <!-- Summary Stats -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h6 class="card-title">Total Collected</h6>
                                    <h3 class="text-success">${{ number_format($summary['total_fee_collected'], 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h6 class="card-title">Outstanding</h6>
                                    <h3 class="text-warning">${{ number_format($summary['total_fee_outstanding'], 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h6 class="card-title">Students</h6>
                                    <h3>{{ $studentFees->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h6 class="card-title">Collection Rate</h6>
                                    <h3>{{ $summary['total_fee_due'] > 0 ? number_format(($summary['total_fee_collected'] / $summary['total_fee_due'] * 100), 1) : 0 }}%</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Record Payment Form -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Record Student Payment</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('finances.student-fees.record') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="student_id" class="form-label">Student</label>
                                        <select name="student_id" id="student_id" class="form-control" required>
                                            <option value="">-- Select Student --</option>
                                            @foreach($studentFees as $fee)
                                            <option value="{{ $fee->student->id }}">{{ $fee->student->first_name }} {{ $fee->student->last_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="amount_paid" class="form-label">Amount Paid ($)</label>
                                        <input type="number" name="amount_paid" id="amount_paid" class="form-control" step="0.01" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="receipt_number" class="form-label">Receipt Number</label>
                                        <input type="text" name="receipt_number" id="receipt_number" class="form-control">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3"><i class="bi bi-check-circle me-2"></i>Record Payment</button>
                            </form>
                        </div>
                    </div>

                    <!-- Student Fees Table -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-table me-2"></i>Student Fee Status</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Student Name</th>
                                            <th>Amount Due</th>
                                            <th>Amount Paid</th>
                                            <th>Outstanding</th>
                                            <th>Status</th>
                                            <th>Payment Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($studentFees as $fee)
                                        <tr>
                                            <td>{{ $fee->student->first_name }} {{ $fee->student->last_name }}</td>
                                            <td>${{ number_format($fee->amount_due, 2) }}</td>
                                            <td class="text-success">${{ number_format($fee->amount_paid, 2) }}</td>
                                            <td class="text-warning">${{ number_format($fee->outstanding, 2) }}</td>
                                            <td>
                                                @if($fee->isPaid())
                                                    <span class="badge bg-success">✓ Paid</span>
                                                @elseif($fee->isPartiallyPaid())
                                                    <span class="badge bg-warning">⚠ Partial</span>
                                                @else
                                                    <span class="badge bg-danger">✗ Pending</span>
                                                @endif
                                            </td>
                                            <td>{{ $fee->payment_date?->format('M d, Y') ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>
</div>
@endsection
