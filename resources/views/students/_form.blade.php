@php
    $groups   = ['Group A', 'Group B', 'Group C', 'Group D'];
    $statuses = ['Active', 'Pending', 'Inactive'];
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Full name</label>
        <input type="text" name="name" value="{{ old('name', $student->name ?? '') }}"
               class="form-control @error('name') is-invalid @enderror">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Student ID</label>
        <input type="text" name="student_code" value="{{ old('student_code', $student->student_code ?? '') }}"
               class="form-control @error('student_code') is-invalid @enderror">
        @error('student_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Group</label>
        <select name="group" class="form-select @error('group') is-invalid @enderror">
            @foreach ($groups as $g)
                <option value="{{ $g }}" @selected(old('group', $student->group ?? '') === $g)>{{ $g }}</option>
            @endforeach
        </select>
        @error('group') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Mark (0-100)</label>
        <input type="number" name="mark" value="{{ old('mark', $student->mark ?? 0) }}"
               class="form-control @error('mark') is-invalid @enderror">
        @error('mark') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror">
            @foreach ($statuses as $s)
                <option value="{{ $s }}" @selected(old('status', $student->status ?? 'Active') === $s)>{{ $s }}</option>
            @endforeach
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>