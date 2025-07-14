<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PENDING_APPROVAL = 'pending_approval';
    case CONFIRMED = 'confirmed';
    case REJECTED = 'rejected';
    case PAID = 'paid';
    case REFUNDED = 'refunded';
    case DELIVERING = 'Delivering';
    case DELIVERED = 'Delivered';

    public function label(): string
    {
        return match ($this) {
            self::PENDING_APPROVAL => 'Pending Approval',
            self::CONFIRMED => 'Confirmed (Awaiting Delivery)',
            self::REJECTED => 'Rejected',
            self::DELIVERING => 'Delivering',
            self::DELIVERED => 'Delivered', // <-- Add the label for it too
        };
}
}
