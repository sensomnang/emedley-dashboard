@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    {{-- BANNER --}}
    <div class="hero-banner mb-4">
        <div>
            <small class="opacity-75">Academic Year 2022-2023</small>
            <h4 class="fw-bold mt-1 mb-3">
                Overall Student Performance Dashboard<br>
                Of ABC School for Class 10
            </h4>
            <a href="#" class="btn btn-light btn-sm rounded-pill px-3">Get Access</a>
        </div>
        <i class="bi bi-bar-chart-line-fill hero-icon"></i>
    </div>

    {{-- STAT CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="stat-icon" style="background:#f4a261;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <span class="badge-soft">+1%</span>
                </div>
                <small class="text-muted d-block mt-3">Total Student</small>
                <h3 class="fw-bold mb-0">{{ $totalStudents }}</h3>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="stat-icon" style="background:#2f7d5b;">
                        <i class="bi bi-person-x-fill"></i>
                    </div>
                    <span class="badge-soft warn">Absent</span>
                </div>
                <small class="text-muted d-block mt-3">Student Absent & LOA</small>
                <h3 class="fw-bold mb-0">{{ $absentRate }}%</h3>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="stat-icon" style="background:#222;">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>
                    <span class="badge-soft">Attendance</span>
                </div>
                <small class="text-muted d-block mt-3">Attendance Rate</small>
                <h3 class="fw-bold mb-0">{{ $attendanceRate }}%</h3>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="stat-icon" style="background:#28c76f;">
                        <i class="bi bi-emoji-smile-fill"></i>
                    </div>
                    <span class="badge-soft">Teacher</span>
                </div>
                <small class="text-muted d-block mt-3">Teacher Satisfaction</small>
                <h3 class="fw-bold mb-0">{{ $satisfaction }}%</h3>
            </div>
        </div>
    </div>
        {{-- CHART --}}
    <div class="panel mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Attendance (Last 5 days)</h6>
            <span class="badge-soft">Live data</span>
        </div>
        <div style="height: 280px;">
            <canvas id="attendanceChart"></canvas>
        </div>
    </div>

    {{-- STUDENT TABLE --}}
    <div class="panel">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h6 class="fw-bold mb-0">Visualize Your Academic Success</h6>

            <form method="GET" action="{{ route('dashboard') }}" class="d-flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="form-control form-control-sm rounded-pill"
                       placeholder="Search name or ID...">
                <button class="btn btn-sm btn-success rounded-pill px-3">Search</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table align-middle table-borderless table-hover">
                <thead class="text-muted small">
                    <tr>
                        <th>Name</th>
                        <th>Student ID</th>
                        <th>Group</th>
                        <th>Mark-sheet</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr>
                            <td class="fw-semibold">{{ $student->name }}</td>
                            <td>{{ $student->student_code }}</td>
                            <td>{{ $student->group }}</td>
                            <td>{{ $student->mark }}/100</td>
                            <td>
                                @php
                                    $color = match($student->status) {
                                        'Active'   => 'success',
                                        'Pending'  => 'warning',
                                        default    => 'secondary',
                                    };
                                @endphp
                                <span class="badge rounded-pill text-bg-{{ $color }}">{{ $student->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No students found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted">
                Showing {{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }}
                of {{ $students->total() }} students
            </small>
                        {{ $students->links() }}
        </div>
    </div>

@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('attendanceChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [
                    {
                        label: 'Present',
                        data: @json($chartPresent),
                        backgroundColor: '#2f7d5b',
                        borderRadius: 6,
                    },
                    {
                        label: 'Absent / Leave',
                        data: @json($chartAbsent),
                        backgroundColor: '#f4a261',
                        borderRadius: 6,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                scales: { y: { beginAtZero: true } },
            },
        });
    </script>
@endpush
