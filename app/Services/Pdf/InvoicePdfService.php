<?php

namespace App\Services\Pdf;

use App\Models\CmsSetting;
use App\Models\Order;
use Spatie\LaravelPdf\Facades\Pdf;
use function Spatie\LaravelPdf\Support\pdf;

class InvoicePdfService
{

    public function generateInvoice(Order $order)
    {
        $dpSetting = CmsSetting::where('key', 'dp_percentage')->first();
        $dpPercentage = $dpSetting ? (int) $dpSetting->value : 50;
        $dpAmount = ($order->total_amount * $dpPercentage) / 100;

        $data = [
            'order_number' => $order->order_number,
            'customer_name' => $order->customer_name,
            'customer_phone' => $order->customer_phone,
            'location' => $order->location,
            'total_amount' => $order->total_amount,
            'dp_amount' => $dpAmount,
            'dp_percentage' => $dpPercentage,
            'status' => $order->status,
            'all_items' => $order->items()->with('product')->get(),
        ];

        return Pdf::view('pdf.invoice', compact('data'))
            ->headerView('pdf.headerInvoice')
            ->footerView('pdf.footerInvoice')
            ->margins(45, 15, 30, 15)
            ->format('A4')->name("invoice-{$order->order_number}.pdf")->download();
    }
}