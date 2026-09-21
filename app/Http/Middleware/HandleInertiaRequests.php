<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;
use App\Services\CmsService;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        // Get cart count from session
        $cart = session()->get('cart', []);
        $cartCount = count($cart);

        $enrichedCart = [];
        if (!empty($cart)) {
            $productIds = collect($cart)->pluck('product_id')->unique()->filter()->values()->all();
            $products = \App\Models\Product::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($cart as $item) {
                $product = $products->get($item['product_id']);
                if ($product) {
                    $enrichedCart[] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => (int) $product->sell_price,
                        'image' => $product->image_url,
                        'qty' => (int) $item['quantity'],
                        'type' => $item['type'] ?? 'satuan',
                        'box_group_id' => $item['box_group_id'] ?? null,
                    ];
                }
            }
        }

        // Get CMS Data
        $cmsService = app(CmsService::class);
        $cmsSettings = $cmsService->getAllSettings();
        $cmsSocials = $cmsService->getSocialMedia();

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'info' => fn () => $request->session()->get('info'),
                'warning' => fn () => $request->session()->get('warning'),
            ],
            'cart' => $enrichedCart,
            'cartCount' => $cartCount,
            'cms' => [
                'settings' => $cmsSettings,
                'socials' => $cmsSocials,
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ];
    }
}
