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
    case PROCESSING = 'processing'; 
    case COMPLETED = 'completed'; 
    case CANCELLED = 'cancelled';


    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PENDING_APPROVAL => 'Pending Approval',
            self::CONFIRMED => 'Confirmed',
            self::REJECTED => 'Rejected',
            self::PAID => 'Paid',
            self::REFUNDED => 'Refunded',
            self::DELIVERING => 'Delivering',
            self::DELIVERED => 'Delivered',
            self::PROCESSING => 'Processing',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
}
}
