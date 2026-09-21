<?php

namespace App\Services\Pdf;

use App\Models\CmsSetting;
use App\Models\Order;
use Spatie\LaravelPdf\Facades\Pdf;
use function Spatie\LaravelPdf\Support\pdf;

class InvoicePdfService
{

    public function prepareInvoiceData(Order $order): array
    {
        $dpSetting = CmsSetting::where('key', 'dp_percentage')->first();
        $defaultDpPercentage = $dpSetting ? (int) $dpSetting->value : 70;
        $isFull = (($order->payment_type ?? 'dp') === 'full' || (float)$order->dp_amount >= (float)$order->total_amount);
        $dpAmount = (float) $order->dp_amount;
        $dpPercentage = $isFull ? 100 : $defaultDpPercentage;
        $remainingAmount = $isFull ? 0 : max(0, (float)$order->total_amount - $dpAmount);

        return [
            'order_number' => $order->order_number,
            'customer_name' => $order->customer_name,
            'customer_phone' => $order->customer_phone,
            'location' => $order->location,
            'total_amount' => (float) $order->total_amount,
            'dp_amount' => $dpAmount,
            'dp_percentage' => $dpPercentage,
            'remaining_amount' => $remainingAmount,
            'is_full' => $isFull,
            'payment_type' => $order->payment_type ?? ($isFull ? 'full' : 'dp'),
            'status' => $order->status,
            'all_items' => $order->items()->with('product')->get(),
        ];
    }

    public function generateInvoice(Order $order)
    {
        $data = $this->prepareInvoiceData($order);

        return Pdf::view('Pdf.invoice', compact('data'))
            ->headerView('Pdf.headerInvoice')
            ->footerView('Pdf.footerInvoice')
            ->margins(45, 15, 30, 15)
            ->withBrowsershot(function ($browsershot) {
                $browsershot->noSandbox();

                $chromePath = env('CHROME_PATH');
                if (! $chromePath) {
                    $defaultLocal = base_path('.cache/puppeteer/chrome-headless-shell/linux-150.0.7871.24/chrome-headless-shell-linux64/chrome-headless-shell');
                    if (file_exists($defaultLocal)) {
                        $chromePath = $defaultLocal;
                    } else {
                        $matches = glob(base_path('.cache/puppeteer/chrome-headless-shell/*/*/chrome-headless-shell'));
                        if (! empty($matches)) {
                            $chromePath = end($matches);
                        }
                    }
                }

                if ($chromePath && file_exists($chromePath)) {
                    $browsershot->setChromePath($chromePath);
                }
            })
            ->format('A4')->name("invoice-{$order->order_number}.pdf")->download();
    }
}