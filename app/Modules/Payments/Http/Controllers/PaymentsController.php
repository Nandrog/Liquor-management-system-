<?php

namespace App\Modules\Payments\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentsController extends Controller
{
    public function index()
{
    //$payments = payments::with('user', 'orderItems.product')->latest()->get();
    return view('orders.payments');
}
}
