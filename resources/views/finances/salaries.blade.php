@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-start">
        @include('layouts.left-menu')
        <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10">
            <div class="row pt-3">
                <div class="col ps-4">
                    <h1 class="display-6 mb-4"><i class="bi bi-people me-2"></i>Teacher Salary Management</h1>

                    <!-- Add Salary Form -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Record Teacher Salary</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('finances.salaries.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="teacher_id" class="form-label">Teacher</label>
                                        <select name="teacher_id" id="teacher_id" class="form-control" required>
                                            <option value="">-- Select Teacher --</option>
                                            @php
                                                $teachers = \App\Models\User::where('role', 'teacher')->get();
                                            @endphp
                                            @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="base_salary" class="form-label">Base Salary ($)</label>
                                        <input type="number" name="base_salary" id="base_salary" class="form-control" step="0.01" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="salary_date" class="form-label">Salary Date</label>
                                        <input type="date" name="salary_date" id="salary_date" class="form-control" required value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <label for="notes" class="form-label">Notes (Optional)</label>
                                        <textarea name="notes" id="notes" class="form-control" rows="2"></textarea>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3"><i class="bi bi-check-circle me-2"></i>Record Salary</button>
                            </form>
                        </div>
                    </div>

                    <!-- Salaries Table -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Teacher Salaries</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Teacher Name</th>
                                            <th>Salary Date</th>
                                            <th>Base Salary</th>
                                            <th>Tax Deduction</th>
                                            <th>Benefits Deduction</th>
                                            <th>Net Salary</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($salaries as $salary)
                                        <tr>
                                            <td>{{ $salary->teacher->first_name }} {{ $salary->teacher->last_name }}</td>
                                            <td>{{ $salary->salary_date->format('M d, Y') }}</td>
                                            <td class="fw-bold">${{ number_format($salary->base_salary, 2) }}</td>
                                            <td class="text-danger">-${{ number_format($salary->tax_deduction, 2) }}</td>
                                            <td class="text-warning">-${{ number_format($salary->benefits_deduction, 2) }}</td>
                                            <td class="text-success fw-bold">${{ number_format($salary->net_salary, 2) }}</td>
                                            <td>
                                                @if($salary->status === 'paid')
                                                    <span class="badge bg-success">✓ Paid</span>
                                                @elseif($salary->status === 'approved')
                                                    <span class="badge bg-info">Approved</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('finances.salaries.delete', $salary->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this salary record?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">No salary records</td>
                                        </tr>
                                        @endforelse
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
