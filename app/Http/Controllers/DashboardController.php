<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {

        $totalStudents = Student::count();
        $totalRecords  = Attendance::count();

        $absentCount  = Attendance::whereIn('status', ['absent', 'leave'])->count();
        $presentCount = Attendance::where('status', 'present')->count();

        $absentRate = $totalRecords > 0 ? round($absentCount / $totalRecords * 100) : 0;
        $attendanceRate = $totalRecords > 0 ? round($presentCount / $totalRecords * 100) : 0;
        $satisfaction = 55;


        $days = collect(range(4, 0))->map(fn ($i) => now()->subDays($i)->toDateString());

        $chartLabels  = $days->map(fn ($d) => date('d M', strtotime($d)))->values();
        $chartPresent = $days->map(fn ($d) => Attendance::where('date', $d)->where('status', 'present')->count())->values();
        $chartAbsent  = $days->map(fn ($d) => Attendance::where('date', $d)->whereIn('status', ['absent', 'leave'])->count())->values();

        $students = Student::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('student_code', 'like', "%{$search}%");
            })
            ->latest('id')
            ->paginate(8)
            ->withQueryString();

        return view('dashboard', compact(
            'totalStudents', 'absentRate', 'attendanceRate', 'satisfaction',
            'chartLabels', 'chartPresent', 'chartAbsent',
            'students'
        ));
    }
}