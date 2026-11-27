@extends('layouts.admin')

@section('title', 'Master Info')
@section('page-title', 'Master info')

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

    {{-- TABS --}}
    <ul class="nav nav-pills master-tabs mb-4" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-teachers" type="button">
                <i class="bi bi-person-badge"></i> Teachers
                <span class="badge-soft ms-1">{{ $teachers->count() }}</span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-groups" type="button">
                <i class="bi bi-collection"></i> Groups
                <span class="badge-soft ms-1">{{ $groups->count() }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content">

        {{-- ================= TEACHERS ================= --}}
        <div class="tab-pane fade show active" id="tab-teachers">
            <div class="panel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">All Teachers</h6>
                    <button class="btn btn-sm btn-success rounded-pill px-3"
                            data-bs-toggle="modal" data-bs-target="#teacherModal"
                            data-mode="create">
                        <i class="bi bi-plus-lg"></i> Add Teacher
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-borderless table-hover">
                        <thead class="text-muted small">
                            <tr>
                                <th>Name</th>
                                <th>Subject</th>
                                <th>Phone</th>
                                <th>Groups</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($teachers as $teacher)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar">{{ strtoupper(substr($teacher->name, 0, 1)) }}</div>
                                            <span class="fw-semibold">{{ $teacher->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $teacher->subject }}</td>
                                    <td>{{ $teacher->phone ?? '-' }}</td>
                                    <td>{{ $teacher->groups_count }}</td>
                                    <td>
                                        <span class="badge rounded-pill text-bg-{{ $teacher->status === 'Active' ? 'success' : 'secondary' }}">
                                            {{ $teacher->status }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-light"
                                                data-bs-toggle="modal" data-bs-target="#teacherModal"
                                                data-mode="edit"
                                                data-url="{{ route('master-info.teachers.update', $teacher) }}"
                                                data-name="{{ $teacher->name }}"
                                                data-subject="{{ $teacher->subject }}"
                                                data-phone="{{ $teacher->phone }}"
                                                data-status="{{ $teacher->status }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <button class="btn btn-sm btn-light text-danger"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                data-name="{{ $teacher->name }}"
                                                data-url="{{ route('master-info.teachers.destroy', $teacher) }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-4">No teachers yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ================= GROUPS ================= --}}
        <div class="tab-pane fade" id="tab-groups">
            <div class="panel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">All Groups</h6>
                    <button class="btn btn-sm btn-success rounded-pill px-3"
                            data-bs-toggle="modal" data-bs-target="#groupModal"
                            data-mode="create">
                        <i class="bi bi-plus-lg"></i> Add Group
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-borderless table-hover">
                        <thead class="text-muted small">
                            <tr>
                                <th>Group</th>
                                <th>Teacher in charge</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($groups as $group)
                                <tr>
                                    <td class="fw-semibold">{{ $group->name }}</td>
                                    <td>{{ $group->teacher?->name ?? '-' }}</td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-light"
                                                data-bs-toggle="modal" data-bs-target="#groupModal"
                                                data-mode="edit"
                                                data-url="{{ route('master-info.groups.update', $group) }}"
                                                data-name="{{ $group->name }}"
                                                data-teacher="{{ $group->teacher_id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <button class="btn btn-sm btn-light text-danger"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                data-name="{{ $group->name }}"
                                                data-url="{{ route('master-info.groups.destroy', $group) }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-4">No groups yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= TEACHER MODAL ================= --}}
    <div class="modal fade" id="teacherModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 p-2">
                <form method="POST" id="teacherForm" action="{{ route('master-info.teachers.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="teacherMethod" value="POST">

                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" id="teacherTitle">Add Teacher</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Full name</label>
                                <input type="text" name="name" id="tName" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Subject</label>
                                <input type="text" name="subject" id="tSubject" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" id="tPhone" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Status</label>
                                <select name="status" id="tStatus" class="form-select">
                                    <option>Active</option>
                                    <option>Inactive</option>
                                </select>
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

    {{-- ================= GROUP MODAL ================= --}}
    <div class="modal fade" id="groupModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 p-2">
                <form method="POST" id="groupForm" action="{{ route('master-info.groups.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="groupMethod" value="POST">

                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" id="groupTitle">Add Group</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Group name</label>
                                <input type="text" name="name" id="gName" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Teacher in charge</label>
                                <select name="teacher_id" id="gTeacher" class="form-select">
                                    <option value="">-- None --</option>
                                    @foreach ($teachers as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->subject }})</option>
                                    @endforeach
                                </select>
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

    {{-- Delete modal (ប្រើ component ចាស់) --}}
    <x-delete-modal />
@endsection

@push('scripts')
<script>
    // ---------- Teacher modal (create / edit) ----------
    document.getElementById('teacherModal').addEventListener('show.bs.modal', function (e) {
        const b = e.relatedTarget;
        if (!b) return;
        const form = document.getElementById('teacherForm');
        const edit = b.dataset.mode === 'edit';

        document.getElementById('teacherTitle').textContent = edit ? 'Edit Teacher' : 'Add Teacher';
        document.getElementById('teacherMethod').value = edit ? 'PUT' : 'POST';
        form.action = edit ? b.dataset.url : "{{ route('master-info.teachers.store') }}";

        document.getElementById('tName').value    = edit ? b.dataset.name : '';
        document.getElementById('tSubject').value = edit ? b.dataset.subject : '';
        document.getElementById('tPhone').value   = edit ? (b.dataset.phone || '') : '';
        document.getElementById('tStatus').value  = edit ? b.dataset.status : 'Active';
    });

    // ---------- Group modal (create / edit) ----------
    document.getElementById('groupModal').addEventListener('show.bs.modal', function (e) {
        const b = e.relatedTarget;
        if (!b) return;
        const form = document.getElementById('groupForm');
        const edit = b.dataset.mode === 'edit';

        document.getElementById('groupTitle').textContent = edit ? 'Edit Group' : 'Add Group';
        document.getElementById('groupMethod').value = edit ? 'PUT' : 'POST';
        form.action = edit ? b.dataset.url : "{{ route('master-info.groups.store') }}";

        document.getElementById('gName').value    = edit ? b.dataset.name : '';
        document.getElementById('gTeacher').value = edit ? (b.dataset.teacher || '') : '';
    });

    const lastTab = sessionStorage.getItem('masterTab');
    if (lastTab) {
        const btn = document.querySelector(`[data-bs-target="${lastTab}"]`);
        if (btn) bootstrap.Tab.getOrCreateInstance(btn).show();
    }
    document.querySelectorAll('.master-tabs [data-bs-toggle="pill"]').forEach(btn => {
        btn.addEventListener('shown.bs.tab', e => {
            sessionStorage.setItem('masterTab', e.target.dataset.bsTarget);
        });
    });
</script>
@endpush