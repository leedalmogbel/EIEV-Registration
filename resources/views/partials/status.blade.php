@php
$status ?? '';
@endphp
@if ($status == 'P')
    <small class="text-warning">PENDING</small>
@elseif ($status == 'A')
    <small class="text-success">APPROVED</small>
@elseif ($status == 'R')
    <small class="text-danger">REJECTED</small>
@else
    <small class="text-secondary">UNKNOWN</small>
@endif
