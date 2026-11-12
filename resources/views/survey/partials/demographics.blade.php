<table class="table table-sm mb-0">
    <thead>
        <tr><th>Demographic</th><th>QID</th><th>Question</th><th>Type</th><th>Answers</th></tr>
    </thead>
    <tbody>
        @forelse ($rows as $demo)
            @php
                $codes = $demo->answer_codes ?? [];
                if (is_string($codes)) {
                    $decoded = json_decode($codes, true);
                    $codes = is_array($decoded) ? $decoded : [];
                } elseif (is_object($codes)) {
                    $codes = json_decode(json_encode($codes), true);
                } else {
                    $codes = is_array($codes) ? $codes : [];
                }
            @endphp
            <tr>
                <td>{{ $demo->demographic_name ?? '-' }}</td>
                <td>{{ $demo->question_id ?? '-' }}</td>
                <td>{{ $demo->question_text ?? '-' }}</td>
                <td>{{ $demo->question_type ?? '-' }}</td>
                <td>
                    @foreach (array_slice($codes, 0, 10) as $item)
                        @php
                            $label = is_array($item)
                                ? (($item['AnswerCode'] ?? '') . ' - ' . ($item['AnswerText'] ?? ''))
                                : (string) $item;
                        @endphp
                        {{ $label }}<br>
                    @endforeach
                    @if (count($codes) > 10) ... @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted">No demographics found.</td></tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-between align-items-center mt-3">
    <button type="button" class="btn btn-sm btn-outline-secondary demo-page"
            data-page="{{ $rows->currentPage() - 1 }}" @disabled($rows->onFirstPage())>Previous</button>
    <span class="text-muted small">Page <strong>{{ $rows->currentPage() }}</strong> of {{ $rows->lastPage() }}</span>
    <button type="button" class="btn btn-sm btn-outline-secondary demo-page"
            data-page="{{ $rows->currentPage() + 1 }}" @disabled(! $rows->hasMorePages())>Next</button>
</div>
