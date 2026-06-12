<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Repositories\ProductRepository;
use App\Services\OrderService;
use App\Services\CartService;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    protected $orderService;
    protected $cartService;
    protected $productRepository;

    public function __construct(
        OrderService $orderService,
        CartService $cartService,
        ProductRepository $productRepository
    ) {
        $this->orderService = $orderService;
        $this->cartService = $cartService;
        $this->productRepository = $productRepository;
    }

    /**
     * Show checkout form
     */
    public function show()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('customer.shop.index')->with('error', 'Keranjang Anda kosong');
        }

        // Validate cart items
        $validation = $this->cartService->validateCartItems($cart);
        if (!$validation['valid']) {
            session()->put('errors', $validation['errors']);
            return redirect()->route('customer.shop.index')->with('error', 'Ada produk yang tidak tersedia');
        }

        // Calculate totals
        $totals = $this->cartService->calculateTotal($cart);

        return Inertia::render('Customer/Checkout/Show', [
            'cart' => $cart,
            'totals' => $totals,
            'title' => 'Checkout',
        ]);
    }

    /**
     * Process checkout and create order
     */
    public function store(StoreOrderRequest $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('customer.shop.index')->with('error', 'Keranjang Anda kosong');
        }

        // Validate cart items again
        $validation = $this->cartService->validateCartItems($cart);
        if (!$validation['valid']) {
            return redirect()->route('customer.shop.index')->with('error', 'Ada produk yang tidak tersedia');
        }

        try {
            // Prepare items for order
            $items = array_map(function ($item) {
                return [
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price_at_order' => $this->productRepository->find($item['product_id'])->sell_price,
                    'type' => $item['type'] ?? 'satuan',
                    'box_group_id' => $item['box_group_id'] ?? null,
                ];
            }, $cart);

            // Create order
            $order = $this->orderService->createOrder($request->validated(), $items);

            // Generate WhatsApp URL
            $adminPhone = '6281234567890'; // TODO: Get from config
            $whatsappUrl = $this->orderService->generateWhatsAppUrl($order, $adminPhone);

            // Clear cart
            session()->forget('cart');

            return Inertia::render('Customer/Checkout/Success', [
                'order' => new OrderResource($order),
                'whatsappUrl' => $whatsappUrl,
                'title' => 'Pesanan Berhasil Dibuat',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get checkout data (API)
     */
    public function getCheckoutData()
    {
        $cart = session()->get('cart', []);
        $totals = $this->cartService->calculateTotal($cart);

        return response()->json([
            'cart' => $cart,
            'totals' => $totals,
        ]);
    }
}
