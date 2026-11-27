@extends('layouts.admin')

@section('title', 'Improvement')
@section('page-title', 'Improvement')

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger py-2 small">
            <ul class="mb-0">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    {{-- STAT CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#2f7d5b;"><i class="bi bi-mortarboard-fill"></i></div>
                <small class="text-muted d-block mt-3">Average score (this month)</small>
                <h3 class="fw-bold mb-0">{{ $avgCurrent }}</h3>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:{{ $change >= 0 ? '#28c76f' : '#e5484d' }};">
                    <i class="bi {{ $change >= 0 ? 'bi-graph-up-arrow' : 'bi-graph-down-arrow' }}"></i>
                </div>
                <small class="text-muted d-block mt-3">Change vs last month</small>
                <h3 class="fw-bold mb-0 {{ $change >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ $change >= 0 ? '+' : '' }}{{ $change }}%
                </h3>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#f4a261;"><i class="bi bi-trophy-fill"></i></div>
                <small class="text-muted d-block mt-3">Best subject</small>
                <h5 class="fw-bold mb-0">{{ $bestSubject?->subject ?? '-' }}</h5>
                <small class="text-muted">{{ $bestSubject ? round($bestSubject->avg, 1) : '' }}</small>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#222;"><i class="bi bi-people-fill"></i></div>
                <small class="text-muted d-block mt-3">Students improved</small>
                <h3 class="fw-bold mb-0">{{ $improvedCount }}</h3>
            </div>
        </div>
    </div>

    {{-- CHART + GROUP --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="panel h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Average score by subject (6 months)</h6>
                    <button class="btn btn-sm btn-success rounded-pill px-3"
                            data-bs-toggle="modal" data-bs-target="#scoreModal">
                        <i class="bi bi-plus-lg"></i> Add Score
                    </button>
                </div>
                <div style="height: 300px;"><canvas id="subjectChart"></canvas></div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="panel h-100">
                <h6 class="fw-bold mb-4">Average by group (this month)</h6>
                @forelse ($groupAvg as $g)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">{{ $g->group }}</span>
                            <span class="fw-semibold">{{ round($g->avg, 1) }}</span>
                        </div>
                        <div class="progress progress-slim">
                            <div class="progress-bar" style="width: {{ round($g->avg) }}%; background: var(--green);"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted small">No data for this month.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- STUDENT TREND --}}
    <div class="panel mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <h6 class="fw-bold mb-0">
                Student progress
                @if ($selected) <span class="text-muted fw-normal">- {{ $selected->name }}</span> @endif
            </h6>

            <form method="GET" action="{{ route('improvement.index') }}">
                <select name="student_id" class="form-select form-select-sm rounded-pill"
                        style="min-width: 260px;" onchange="this.form.submit()">
                    <option value="">-- Select a student --</option>
                    @foreach ($students as $s)
                        <option value="{{ $s->id }}" @selected(optional($selected)->id === $s->id)>
                            {{ $s->name }} ({{ $s->student_code }})
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        @if ($selected)
            <div style="height: 240px;"><canvas id="studentChart"></canvas></div>
        @else
            <div class="text-center text-muted py-5">
                <i class="bi bi-person-lines-fill fs-1 d-block mb-2"></i>
                Select a student to see their monthly progress.
            </div>
        @endif
    </div>

    {{-- TOP / ATTENTION --}}
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="panel h-100">
                <h6 class="fw-bold mb-3">🏆 Top improvers</h6>
                @include('improvement._list', ['items' => $topImprovers, 'up' => true])
            </div>
        </div>
        <div class="col-lg-6">
            <div class="panel h-100">
                <h6 class="fw-bold mb-3">⚠️ Needs attention</h6>
                @include('improvement._list', ['items' => $needAttention, 'up' => false])
            </div>
        </div>
    </div>

    {{-- ADD SCORE MODAL --}}
    <div class="modal fade" id="scoreModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 p-2">
                <form method="POST" action="{{ route('improvement.store') }}">
                    @csrf
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold">Add / Update Score</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Student</label>
                                <select name="student_id" class="form-select" required>
                                    <option value="">-- Select student --</option>
                                    @foreach ($students as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->student_code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Subject</label>
                                <select name="subject" class="form-select" required>
                                    @foreach ($subjects as $sub) <option>{{ $sub }}</option> @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Month</label>
                                <input type="month" name="month" value="{{ now()->format('Y-m') }}"
                                       class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Score (0-100)</label>
                                <input type="number" name="score" min="0" max="100" class="form-control" required>
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
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        const colors = ['#2f7d5b', '#f4a261', '#4c8bf5', '#c9a45c'];

        // ---------- Subjects chart ----------
        new Chart(document.getElementById('subjectChart'), {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: @json($datasets).map((d, i) => ({
                    ...d,
                    borderColor: colors[i],
                    backgroundColor: colors[i],
                    tension: 0.4,
                    pointRadius: 4,
                })),
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                scales: { y: { min: 0, max: 100 } },
            },
        });

        @if ($selected)
        // ---------- Student chart ----------
        new Chart(document.getElementById('studentChart'), {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Average score',
                    data: @json($trend),
                    backgroundColor: '#2f7d5b',
                    borderRadius: 8,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { min: 0, max: 100 } },
            },
        });
        @endif
    </script>
@endpush