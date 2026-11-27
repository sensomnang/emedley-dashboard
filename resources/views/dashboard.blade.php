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

@endsection