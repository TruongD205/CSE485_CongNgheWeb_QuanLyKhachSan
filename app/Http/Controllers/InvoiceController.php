<?php

namespace App\Http\Controllers;

use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['booking.room'])->latest('PaymentDate')->paginate(10);
        $totalRevenue = Invoice::sum('TotalAmount');

        return view('invoices.index', compact('invoices', 'totalRevenue'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['booking.room']);

        return view('invoices.show', compact('invoice'));
    }
}

