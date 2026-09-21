<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;
use Inertia\Inertia;

class CartController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Display cart page
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $cartWithProducts = array_map(function ($item) {
            $product = $this->productRepository->find($item['product_id']);
            return [
                'product_id' => $item['product_id'],
                'product' => new ProductResource($product),
                'quantity' => $item['quantity'],
                'type' => $item['type'] ?? 'satuan',
                'box_group_id' => $item['box_group_id'] ?? null,
            ];
        }, $cart);

        return Inertia::render('Customer/Cart/Index', [
            'cart' => $cartWithProducts,
            'cartCount' => count($cart),
            'title' => 'Keranjang Belanja',
        ]);
    }

    /**
     * Get cart items with product details for frontend CartStore (API)
     */
    public function getItems()
    {
        $cart = session()->get('cart', []);
        $items = [];

        foreach ($cart as $item) {
            try {
                $product = $this->productRepository->find($item['product_id']);
                if ($product) {
                    $items[] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => (int) $product->sell_price,
                        'image' => $product->image_url,
                        'qty' => (int) $item['quantity'],
                        'type' => $item['type'] ?? 'satuan',
                        'box_group_id' => $item['box_group_id'] ?? null,
                    ];
                }
            } catch (\Exception $e) {
                // Skip if product not found
            }
        }

        return response()->json([
            'items' => $items,
            'count' => count($items),
        ]);
    }

    /**
     * Add item to cart (API)
     */
    public function add()
    {
        $productId = request()->get('product_id');
        $quantity = request()->get('quantity', 1);
        $type = request()->get('type', 'satuan');
        $boxGroupId = request()->get('box_group_id');

        // Validate product exists
        try {
            $product = $this->productRepository->find($productId);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }

        // Get cart
        $cart = session()->get('cart', []);

        // Check if product already in cart
        $found = false;
        foreach ($cart as &$item) {
            if ($item['product_id'] == $productId && $item['type'] == $type) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }

        // Add new item if not found
        if (!$found) {
            $cart[] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'type' => $type,
                'box_group_id' => $boxGroupId,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'cartCount' => count($cart),
            'message' => 'Produk ditambahkan ke keranjang',
        ]);
    }

    /**
     * Remove item from cart (API)
     */
    public function remove()
    {
        $productId = request()->get('product_id');

        $cart = session()->get('cart', []);
        $cart = array_filter($cart, function ($item) use ($productId) {
            return $item['product_id'] != $productId;
        });

        session()->put('cart', array_values($cart));

        return response()->json([
            'success' => true,
            'cartCount' => count($cart),
            'message' => 'Produk dihapus dari keranjang',
        ]);
    }

    /**
     * Update cart item quantity (API)
     */
    public function updateQuantity()
    {
        $productId = request()->get('product_id');
        $quantity = request()->get('quantity', 1);

        $cart = session()->get('cart', []);

        foreach ($cart as &$item) {
            if ($item['product_id'] == $productId) {
                if ($quantity <= 0) {
                    // Remove if quantity is 0 or less
                    $cart = array_filter($cart, fn($i) => $i['product_id'] != $productId);
                } else {
                    $item['quantity'] = $quantity;
                }
                break;
            }
        }

        session()->put('cart', array_values($cart));

        return response()->json([
            'success' => true,
            'cartCount' => count($cart),
        ]);
    }

    /**
     * Clear cart (API)
     */
    public function clear()
    {
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'cartCount' => 0,
            'message' => 'Keranjang dikosongkan',
        ]);
    }

    /**
     * Get cart count
     */
    public function getCount()
    {
        $cart = session()->get('cart', []);
        return response()->json(['count' => count($cart)]);
    }
}
