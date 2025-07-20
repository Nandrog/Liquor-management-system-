<?php

namespace App\Enums;

/**
 * Defines the possible payment statuses for an order.
 * This is a "Backed Enum", which means each case has an associated string value.
 */
enum PaymentStatus: string
{
    /**
     * The order has been placed, but payment has not yet been completed.
     */
    case PENDING = 'pending';

    /**
     * The payment has been successfully processed.
     */
    case PAID = 'paid';

    /**
     * The payment attempt was made but failed.
     */
    case FAILED = 'failed';

    /**
     * The payment was successfully processed and later refunded.
     */
    case REFUNDED = 'refunded';
}
