@extends('layouts.admin')

@section('title', 'Edit Student')
@section('page-title', 'Edit Student')

@section('content')
    <div class="panel">
        <form method="POST" action="{{ route('students.update', $student) }}">
            @csrf
            @method('PUT')
            @include('students._form')

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-success rounded-pill px-4">Update</button>
                <a href="{{ route('students.index') }}" class="btn btn-light rounded-pill px-4">Cancel</a>
            </div>
        </form>
    </div>
@endsection