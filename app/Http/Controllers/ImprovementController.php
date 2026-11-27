<?php

namespace App\Http\Controllers;

use App\Models\Score;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ImprovementController extends Controller
{
    private array $subjects = ['Mathematics', 'English', 'Physics', 'Chemistry'];

    public function index(Request $request)
    {
        $current  = now()->format('Y-m');
        $previous = now()->subMonth()->format('Y-m');
        $months   = collect(range(5, 0))->map(fn ($i) => now()->subMonths($i)->format('Y-m'));
        $labels   = $months->map(fn ($m) => date('M Y', strtotime($m . '-01')))->values();

        // ===== Stat cards =====
        $avgCurrent = round(Score::where('month', $current)->avg('score') ?? 0, 1);
        $avgPrev    = round(Score::where('month', $previous)->avg('score') ?? 0, 1);
        $change     = $avgPrev > 0 ? round(($avgCurrent - $avgPrev) / $avgPrev * 100, 1) : 0;

        $bestSubject = Score::where('month', $current)
            ->select('subject', DB::raw('AVG(score) as avg'))
            ->groupBy('subject')
            ->orderByDesc('avg')
            ->first();


        $rows = Score::select('subject', 'month', DB::raw('AVG(score) as avg'))
            ->whereIn('month', $months)
            ->groupBy('subject', 'month')
            ->get();

        $datasets = collect($this->subjects)->map(fn ($sub) => [
            'label' => $sub,
            'data'  => $months->map(fn ($m) => round(
                $rows->first(fn ($r) => $r->subject === $sub && $r->month === $m)?->avg ?? 0, 1
            ))->values(),
        ])->values();


        $groupAvg = Score::join('students', 'students.id', '=', 'scores.student_id')
            ->where('scores.month', $current)
            ->groupBy('students.group')
            ->orderBy('students.group')
            ->select('students.group', DB::raw('AVG(scores.score) as avg'))
            ->get();


        $changes = Student::query()
            ->select('students.*')
            ->selectRaw('(SELECT AVG(score) FROM scores WHERE scores.student_id = students.id AND scores.month = ?) as cur', [$current])
            ->selectRaw('(SELECT AVG(score) FROM scores WHERE scores.student_id = students.id AND scores.month = ?) as prev', [$previous])
            ->get()
            ->filter(fn ($s) => $s->cur !== null && $s->prev !== null)
            ->map(function ($s) {
                $s->diff = round($s->cur - $s->prev, 1);
                return $s;
            });

        $improvedCount = $changes->where('diff', '>', 0)->count();
        $topImprovers  = $changes->sortByDesc('diff')->take(5)->values();
        $needAttention = $changes->sortBy('diff')->take(5)->values();

        // ===== Student trend =====
        $students = Student::orderBy('name')->get(['id', 'name', 'student_code']);
        $selected = $request->student_id ? Student::find($request->student_id) : null;
        $trend    = null;

        if ($selected) {
            $trend = $months->map(fn ($m) => round(
                Score::where('student_id', $selected->id)->where('month', $m)->avg('score') ?? 0, 1
            ))->values();
        }

        return view('improvement.index', [
            'subjects'      => $this->subjects,
            'avgCurrent'    => $avgCurrent,
            'change'        => $change,
            'bestSubject'   => $bestSubject,
            'improvedCount' => $improvedCount,
            'labels'        => $labels,
            'datasets'      => $datasets,
            'groupAvg'      => $groupAvg,
            'topImprovers'  => $topImprovers,
            'needAttention' => $needAttention,
            'students'      => $students,
            'selected'      => $selected,
            'trend'         => $trend,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject'    => ['required', Rule::in($this->subjects)],
            'month'      => 'required|date_format:Y-m',
            'score'      => 'required|integer|min:0|max:100',
        ]);

        Score::updateOrCreate(
            ['student_id' => $data['student_id'], 'subject' => $data['subject'], 'month' => $data['month']],
            ['score' => $data['score']]
        );

        return redirect()->route('improvement.index', ['student_id' => $data['student_id']])
            ->with('success', 'Score saved successfully.');
    }
}