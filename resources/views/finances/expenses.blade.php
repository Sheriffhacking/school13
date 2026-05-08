@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-start">
        @include('layouts.left-menu')
        <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10">
            <div class="row pt-3">
                <div class="col ps-4">
                    <h1 class="display-6 mb-4"><i class="bi bi-cash-flow me-2"></i>Expense Management</h1>

                    <!-- Add Expense Form -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Record New Expense</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('finances.expenses.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="category_id" class="form-label">Category</label>
                                        <select name="category_id" id="category_id" class="form-control" required>
                                            <option value="">-- Select Category --</option>
                                            @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="amount" class="form-label">Amount ($)</label>
                                        <input type="number" name="amount" id="amount" class="form-control" step="0.01" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="expense_date" class="form-label">Date</label>
                                        <input type="date" name="expense_date" id="expense_date" class="form-control" required value="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="description" class="form-label">Description</label>
                                        <input type="text" name="description" id="description" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <label for="notes" class="form-label">Notes (Optional)</label>
                                        <textarea name="notes" id="notes" class="form-control" rows="2"></textarea>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3"><i class="bi bi-check-circle me-2"></i>Record Expense</button>
                            </form>
                        </div>
                    </div>

                    <!-- Expenses Table -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Recent Expenses</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Category</th>
                                            <th>Description</th>
                                            <th>Amount</th>
                                            <th>Notes</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($expenses as $expense)
                                        <tr>
                                            <td>{{ $expense->expense_date->format('M d, Y') }}</td>
                                            <td><span class="badge bg-info">{{ $expense->category->name }}</span></td>
                                            <td>{{ $expense->description }}</td>
                                            <td class="text-danger fw-bold">${{ number_format($expense->amount, 2) }}</td>
                                            <td><small>{{ $expense->notes }}</small></td>
                                            <td>
                                                <form action="{{ route('finances.expenses.delete', $expense->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this expense?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No expenses recorded</td>
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
