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
        $cartItems = $request->input('items', []);

        if (empty($cartItems)) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Keranjang Anda kosong'], 422);
            }
            return redirect()->route('customer.shop.index')->with('error', 'Keranjang Anda kosong');
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
            }, $cartItems);

            // Create order
            $order = $this->orderService->createOrder($request->validated(), $items);

            // Generate WhatsApp URL
            // Mengambil nomor WA admin dari CMS Setting jika ada
            $adminPhoneSetting = \App\Models\CmsSetting::where('key', 'contact_phone')->first();
            $adminPhone = $adminPhoneSetting ? $adminPhoneSetting->value : '6281234567890';

            // Format phone number jika mulai dari 0 diubah ke 62
            if (strpos($adminPhone, '0') === 0) {
                $adminPhone = '62' . substr($adminPhone, 1);
            }

            $businessNameSetting = \App\Models\CmsSetting::where('key', 'business_name')->first();
            $businessName = $businessNameSetting ? $businessNameSetting->value : 'Snack Box Custom';

            $whatsappUrl = $this->orderService->generateWhatsAppUrl($order, $adminPhone, $businessName);

            // Clear cart
            session()->forget('cart');

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'order' => new OrderResource($order),
                    'whatsappUrl' => $whatsappUrl
                ]);
            }

            return Inertia::render('Customer/Checkout/Success', [
                'order' => new OrderResource($order),
                'whatsappUrl' => $whatsappUrl,
                'title' => 'Pesanan Berhasil Dibuat',
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Checkout Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Terjadi kesalahan Server: ' . $e->getMessage(),
                    'error' => true
                ], 500);
            }
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
