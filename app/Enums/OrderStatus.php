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
}