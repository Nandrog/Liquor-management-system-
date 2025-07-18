<?php

namespace App\Enums;

enum OrderType: string
{
    case SUPPLIER_ORDER = 'supplier_order';
    case VENDOR_ORDER = 'vendor_order';
    case CUSTOMER_ORDER = 'customer_order';
    case PURCHASE = 'purchase';
    case RETURN = 'return';
    case TRANSFER = 'transfer';
    case COMPLETED = 'completed';




}