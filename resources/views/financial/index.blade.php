@extends('layouts.admin')

@section('title', 'Financial')
@section('page-title', 'Financial')

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger py-2 small">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- STAT CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background:#2f7d5b;"><i class="bi bi-cash-stack"></i></div>
                <small class="text-muted d-block mt-3">Total Collected</small>
                <h3 class="fw-bold mb-0">${{ number_format($totalPaid, 2) }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background:#e5484d;"><i class="bi bi-exclamation-circle-fill"></i></div>
                <small class="text-muted d-block mt-3">Outstanding (Pending + Overdue)</small>
                <h3 class="fw-bold mb-0">${{ number_format($totalOwed, 2) }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background:#f4a261;"><i class="bi bi-receipt"></i></div>
                <small class="text-muted d-block mt-3">Transactions this month</small>
                <h3 class="fw-bold mb-0">{{ $thisMonthCnt }}</h3>
            </div>
        </div>
    </div>

    {{-- CHART --}}
    <div class="panel mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Revenue (Last 6 months)</h6>
            <span class="badge-soft">Paid only</span>
        </div>
        <div style="height: 260px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="panel">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h6 class="fw-bold mb-0">Payments</h6>

            <div class="d-flex gap-2 flex-wrap">
                <form method="GET" action="{{ route('financial.index') }}" class="d-flex gap-2">
                    <select name="status" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()">
                        <option value="">All status</option>
                        @foreach (['Paid', 'Pending', 'Overdue'] as $s)
                            <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control form-control-sm rounded-pill" placeholder="Search student...">
                    <button class="btn btn-sm btn-outline-success rounded-pill px-3">Search</button>
                </form>

                <button class="btn btn-sm btn-success rounded-pill px-3"
                        data-bs-toggle="modal" data-bs-target="#paymentModal" data-mode="create">
                    <i class="bi bi-plus-lg"></i> Add Payment
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle table-borderless table-hover">
                <thead class="text-muted small">
                    <tr>
                        <th>Student</th>
                        <th>Month</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $p)
                        @php
                            $color = match($p->status) {
                                'Paid'    => 'success',
                                'Pending' => 'warning',
                                default   => 'danger',
                            };
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar">{{ strtoupper(substr($p->student->name, 0, 1)) }}</div>
                                    <div class="lh-sm">
                                        <div class="fw-semibold">{{ $p->student->name }}</div>
                                        <small class="text-muted">{{ $p->student->student_code }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ date('M Y', strtotime($p->month . '-01')) }}</td>
                            <td class="fw-semibold">${{ number_format($p->amount, 2) }}</td>
                            <td>{{ $p->method }}</td>
                            <td>{{ $p->paid_at->format('d M Y') }}</td>
                            <td><span class="badge rounded-pill text-bg-{{ $color }}">{{ $p->status }}</span></td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-light"
                                        data-bs-toggle="modal" data-bs-target="#paymentModal"
                                        data-mode="edit"
                                        data-url="{{ route('financial.update', $p) }}"
                                        data-student="{{ $p->student_id }}"
                                        data-amount="{{ $p->amount }}"
                                        data-date="{{ $p->paid_at->format('Y-m-d') }}"
                                        data-method="{{ $p->method }}"
                                        data-status="{{ $p->status }}"
                                        data-note="{{ $p->note }}">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <button class="btn btn-sm btn-light text-danger"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        data-name="payment of {{ $p->student->name }}"
                                        data-url="{{ route('financial.destroy', $p) }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No payments found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted">
                Showing {{ $payments->firstItem() ?? 0 }} - {{ $payments->lastItem() ?? 0 }}
                of {{ $payments->total() }} payments
            </small>
            {{ $payments->links('pagination::bootstrap-5') }}
        </div>
    </div>

    {{-- PAYMENT MODAL --}}
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 p-2">
                <form method="POST" id="paymentForm" action="{{ route('financial.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="paymentMethod" value="POST">

                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" id="paymentTitle">Add Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Student</label>
                                <select name="student_id" id="pStudent" class="form-select" required>
                                    <option value="">-- Select student --</option>
                                    @foreach ($students as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->student_code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Amount ($)</label>
                                <input type="number" step="0.01" min="0" name="amount" id="pAmount" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date</label>
                                <input type="date" name="paid_at" id="pDate" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Method</label>
                                <select name="method" id="pMethod" class="form-select">
                                    @foreach (['Cash', 'Bank', 'ABA', 'Wing'] as $m)
                                        <option>{{ $m }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" id="pStatus" class="form-select">
                                    @foreach (['Paid', 'Pending', 'Overdue'] as $s)
                                        <option>{{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Note (optional)</label>
                                <input type="text" name="note" id="pNote" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-success rounded-pill px-4">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-delete-modal />
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        // ---------- Chart ----------
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Revenue ($)',
                    data: @json($chartData),
                    borderColor: '#2f7d5b',
                    backgroundColor: 'rgba(47, 125, 91, 0.15)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } },
            },
        });

        // ---------- Payment modal (create / edit) ----------
        document.getElementById('paymentModal').addEventListener('show.bs.modal', function (e) {
            const b = e.relatedTarget;
            if (!b) return;
            const edit = b.dataset.mode === 'edit';

            document.getElementById('paymentTitle').textContent = edit ? 'Edit Payment' : 'Add Payment';
            document.getElementById('paymentMethod').value = edit ? 'PUT' : 'POST';
            document.getElementById('paymentForm').action =
                edit ? b.dataset.url : "{{ route('financial.store') }}";

            document.getElementById('pStudent').value = edit ? b.dataset.student : '';
            document.getElementById('pAmount').value  = edit ? b.dataset.amount : '';
            document.getElementById('pDate').value    = edit ? b.dataset.date : new Date().toISOString().slice(0, 10);
            document.getElementById('pMethod').value  = edit ? b.dataset.method : 'Cash';
            document.getElementById('pStatus').value  = edit ? b.dataset.status : 'Paid';
            document.getElementById('pNote').value    = edit ? (b.dataset.note || '') : '';
        });
    </script>
@endpush