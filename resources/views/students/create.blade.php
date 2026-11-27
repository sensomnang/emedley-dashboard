@extends('layouts.admin')

@section('title', 'Add Student')
@section('page-title', 'Add Student')

@section('content')
    <div class="panel">
        <form method="POST" action="{{ route('students.store') }}">
            @csrf
            @include('students._form')

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-success rounded-pill px-4">Save</button>
                <a href="{{ route('students.index') }}" class="btn btn-light rounded-pill px-4">Cancel</a>
            </div>
        </form>
    </div>
@endsection