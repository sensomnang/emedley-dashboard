<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FinancialController extends Controller
{
    public function index(Request $request)
    {
        // ===== Stat cards =====
        $totalPaid    = Payment::where('status', 'Paid')->sum('amount');
        $totalOwed    = Payment::whereIn('status', ['Pending', 'Overdue'])->sum('amount');
        $thisMonthCnt = Payment::where('month', now()->format('Y-m'))->count();

        // ===== Chart: ចំណូល ៦ ខែចុងក្រោយ =====
        $months = collect(range(5, 0))->map(fn ($i) => now()->subMonths($i)->format('Y-m'));

        $chartLabels = $months->map(fn ($m) => date('M Y', strtotime($m . '-01')))->values();
        $chartData   = $months->map(fn ($m) => (float) Payment::where('month', $m)
                            ->where('status', 'Paid')->sum('amount'))->values();

        // ===== Table =====
        $payments = Payment::with('student')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->search, function ($q, $search) {
                $q->whereHas('student', function ($s) use ($search) {
                    $s->where('name', 'like', "%{$search}%")
                      ->orWhere('student_code', 'like', "%{$search}%");
                });
            })
            ->latest('paid_at')
            ->paginate(10)
            ->withQueryString();

        $students = Student::orderBy('name')->get(['id', 'name', 'student_code']);

        return view('financial.index', compact(
            'totalPaid', 'totalOwed', 'thisMonthCnt',
            'chartLabels', 'chartData', 'payments', 'students'
        ));
    }

    public function store(Request $request)
    {
        Payment::create($this->validated($request));

        return redirect()->route('financial.index')
            ->with('success', 'Payment recorded successfully.');
    }

    public function update(Request $request, Payment $payment)
    {
        $payment->update($this->validated($request));

        return redirect()->route('financial.index')
            ->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('financial.index')
            ->with('success', 'Payment deleted successfully.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount'     => 'required|numeric|min:0|max:99999999',
            'paid_at'    => 'required|date',
            'method'     => ['required', Rule::in(['Cash', 'Bank', 'ABA', 'Wing'])],
            'status'     => ['required', Rule::in(['Paid', 'Pending', 'Overdue'])],
            'note'       => 'nullable|string|max:255',
        ]);

        $data['month'] = date('Y-m', strtotime($data['paid_at']));

        return $data;
    }
}