<?php

namespace App\Http\Controllers;

use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['booking.room'])->latest('PaymentDate')->paginate(10);
        $totalRevenue = Invoice::where('PaymentStatus', '1')->sum('TotalAmount');

        return view('invoices.index', compact('invoices', 'totalRevenue'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['booking.room', 'booking.services']);

        return view('invoices.show', compact('invoice'));
    }

    public function markAsPaid(Invoice $invoice)
    {
        $invoice->update([
            'PaymentStatus' => '1',
            'PaymentDate' => now(),
        ]);

        return back()->with('success', 'Đã xác nhận thanh toán hóa đơn ' . $invoice->InvoiceCode);
    }
}

