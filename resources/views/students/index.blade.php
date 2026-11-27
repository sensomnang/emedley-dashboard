@extends('layouts.admin')

@section('title', 'Students')
@section('page-title', 'Students profile')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="panel">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h6 class="fw-bold mb-0">All Students</h6>

            <div class="d-flex gap-2">
                <form method="GET" action="{{ route('students.index') }}" class="d-flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control form-control-sm rounded-pill" placeholder="Search name or ID...">
                    <button class="btn btn-sm btn-outline-success rounded-pill px-3">Search</button>
                </form>
                <a href="{{ route('students.create') }}" class="btn btn-sm btn-success rounded-pill px-3">
                    <i class="bi bi-plus-lg"></i> Add Student
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle table-borderless table-hover">
                <thead class="text-muted small">
                    <tr>
                        <th>Name</th>
                        <th>Student ID</th>
                        <th>Group</th>
                        <th>Mark</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
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
                                        'Active'  => 'success',
                                        'Pending' => 'warning',
                                        default   => 'secondary',
                                    };
                                @endphp
                                <span class="badge rounded-pill text-bg-{{ $color }}">{{ $student->status }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('students.edit', $student) }}"
                                   class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>

                                <form method="POST" action="{{ route('students.destroy', $student) }}"
                                      class="d-inline"
                                      onsubmit="return confirm('Delete {{ $student->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="btn btn-sm btn-light text-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal"
                                            data-name="{{ $student->name }}"
                                            data-url="{{ route('students.destroy', $student) }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No students found</td>
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
            {{ $students->links('pagination::bootstrap-5') }}
        </div>
    </div>
        <x-delete-modal />
@endsection