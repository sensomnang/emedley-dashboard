@forelse ($items as $s)
    <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
        <div class="d-flex align-items-center gap-2">
            <div class="avatar">{{ strtoupper(substr($s->name, 0, 1)) }}</div>
            <div class="lh-sm">
                <div class="fw-semibold">{{ $s->name }}</div>
                <small class="text-muted">{{ $s->student_code }} · {{ $s->group }}</small>
            </div>
        </div>
        <div class="text-end lh-sm">
            <div class="fw-bold {{ $s->diff >= 0 ? 'text-success' : 'text-danger' }}">
                {{ $s->diff >= 0 ? '+' : '' }}{{ $s->diff }}
            </div>
            <small class="text-muted">{{ round($s->prev, 1) }} → {{ round($s->cur, 1) }}</small>
        </div>
    </div>
@empty
    <p class="text-muted small mb-0">No data yet.</p>
@endforelse