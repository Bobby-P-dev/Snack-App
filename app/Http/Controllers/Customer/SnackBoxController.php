<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\SnackBoxPackage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SnackBoxController extends Controller
{
    /**
     * Display list of snack box packages
     */
    public function index()
    {
        $packages = SnackBoxPackage::where('is_active', true)->orderBy('capacity')->get();

        // Fallback default packages if table is empty
        if ($packages->isEmpty()) {
            $defaultData = [
                ['name' => 'Snack Box 3 Kue', 'slug' => 'snack-box-3-kue', 'capacity' => 3, 'box_price' => 2500, 'description' => 'Paket hemat untuk acara santai, arisan, atau snack rapat singkat.'],
                ['name' => 'Snack Box 4 Kue', 'slug' => 'snack-box-4-kue', 'capacity' => 4, 'box_price' => 2500, 'description' => 'Paling populer! Kombinasi pas 2 kue manis, 1 asin gurih, dan 1 puding/roti.'],
                ['name' => 'Snack Box 5 Kue', 'slug' => 'snack-box-5-kue', 'capacity' => 5, 'box_price' => 3000, 'description' => 'Paket komplit premium untuk acara resmi, pernikahan, atau seminar besar.'],
            ];
            foreach ($defaultData as $data) {
                SnackBoxPackage::create($data);
            }
            $packages = SnackBoxPackage::where('is_active', true)->orderBy('capacity')->get();
        }

        return Inertia::render('Customer/SnackBox/Index', [
            'packages' => $packages,
            'title' => 'Custom Snack Box - Padu Kue',
        ]);
    }

    /**
     * Display interactive snack box customizer
     */
    public function builder(SnackBoxPackage $package)
    {
        $categories = Category::with(['products' => function ($q) {
            $q->where('is_active', true);
        }])->get();

        $products = Product::where('is_active', true)->get();

        return Inertia::render('Customer/SnackBox/Builder', [
            'package' => $package,
            'categories' => CategoryResource::collection($categories)->resolve(request()),
            'products' => ProductResource::collection($products)->resolve(request()),
            'title' => 'Rakit ' . $package->name . ' - Padu Kue',
        ]);
    }

    /**
     * Add assembled custom snack box to cart
     */
    public function addBoxToCart(Request $request)
    {
        $minBox = (int) (\App\Models\CmsSetting::where('key', 'min_order_box')->value('value') ?? 10);
        if ($minBox < 1) {
            $minBox = 10;
        }

        $validated = $request->validate([
            'package_id' => 'required|exists:snack_box_packages,id',
            'box_quantity' => "required|integer|min:{$minBox}",
            'selected_items' => 'required|array|min:1',
            'selected_items.*.product_id' => 'required|exists:products,id',
            'selected_items.*.quantity' => 'required|integer|min:1',
        ], [
            'box_quantity.min' => "Minimal pemesanan snack box adalah {$minBox} box.",
            'box_quantity.required' => 'Jumlah box wajib diisi.',
            'box_quantity.integer' => 'Jumlah box harus berupa angka bulat.',
        ]);

        $package = SnackBoxPackage::findOrFail($validated['package_id']);
        $totalItemsInBox = collect($validated['selected_items'])->sum('quantity');

        if ($totalItemsInBox !== $package->capacity) {
            return response()->json([
                'success' => false,
                'message' => "Jumlah kue dalam satu box harus tepat {$package->capacity} item (saat ini: {$totalItemsInBox}).",
            ], 422);
        }

        $boxGroupId = (int) (now()->timestamp . rand(100, 999));
        $cart = session()->get('cart', []);

        foreach ($validated['selected_items'] as $item) {
            $cart[] = [
                'product_id' => (int) $item['product_id'],
                'quantity' => (int) $item['quantity'] * (int) $validated['box_quantity'],
                'type' => 'kustom_box',
                'box_group_id' => $boxGroupId,
            ];
        }

        session()->put('cart', $cart);

        $productRepository = app(\App\Repositories\ProductRepository::class);
        $enrichedItems = [];
        foreach ($cart as $cartItem) {
            try {
                $product = $productRepository->find($cartItem['product_id']);
                if ($product) {
                    $enrichedItems[] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => (int) $product->sell_price,
                        'image' => $product->image_url,
                        'qty' => (int) $cartItem['quantity'],
                        'type' => $cartItem['type'] ?? 'satuan',
                        'box_group_id' => $cartItem['box_group_id'] ?? null,
                    ];
                }
            } catch (\Exception $e) {}
        }

        return response()->json([
            'success' => true,
            'cartCount' => count($cart),
            'items' => $enrichedItems,
            'message' => "{$validated['box_quantity']} {$package->name} berhasil ditambahkan ke keranjang!",
        ]);
    }
}
