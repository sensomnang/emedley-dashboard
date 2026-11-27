<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = Student::count();
        $totalRecords  = Attendance::count();

        $absentCount  = Attendance::whereIn('status', ['absent', 'leave'])->count();
        $presentCount = Attendance::where('status', 'present')->count();

        $absentRate = $totalRecords > 0
            ? round($absentCount / $totalRecords * 100)
            : 0;

        $attendanceRate = $totalRecords > 0
            ? round($presentCount / $totalRecords * 100)
            : 0;

        $satisfaction = 55; // សិនសិន (នឹងធ្វើតារាងផ្សេងពេលក្រោយ)

        return view('dashboard', compact(
            'totalStudents',
            'absentRate',
            'attendanceRate',
            'satisfaction'
        ));
    }
}