<?php

namespace App\Services\Pdf;

use App\Models\Order;
use Spatie\LaravelPdf\Facades\Pdf;
use function Spatie\LaravelPdf\Support\pdf;

class InvoicePdfService
{

    public function generateInvoice(Order $order)
    {
        $data = [
            'order_number' => $order->order_number,
            'customer_name' => $order->customer_name,
            'customer_phone' => $order->customer_phone,
            'location' => $order->location,
            'total_amount' => $order->total_amount,
            'dp_amount' => $order->dp_amount,
            'status' => $order->status,
        ];

        return Pdf::view('pdf.invoice', compact('data'))->format('A4')->name("invoice-{$order->order_number}.pdf")->download();
    }
}