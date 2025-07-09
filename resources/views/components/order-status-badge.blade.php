@props(['status'])

@php
    $color = match($status) {
        \App\Enums\OrderStatus::PENDING, \App\Enums\OrderStatus::PENDING_APPROVAL => 'warning',
        \App\Enums\OrderStatus::CONFIRMED, \App\Enums\OrderStatus::PAID => 'success',
        \App\Enums\OrderStatus::REJECTED, \App\Enums\OrderStatus::REFUNDED => 'danger',
        default => 'secondary',
    };
@endphp

<span class="badge bg-{{ $color }}-subtle text-{{ $color }}-emphasis rounded-pill">
    {{ str_replace('_', ' ', $status->value) }}
</span>