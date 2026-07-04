<?php

namespace App\Http\Controllers\Pdf;

use App\Models\Order;
use App\Services\Pdf\InvoicePdfService;

class InvoicePdfController
{

    public function __construct(private InvoicePdfService $service)
    {

    }

    public function index(Order $order)
    {
        return $this->service->generateInvoice($order);
    }
}