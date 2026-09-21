<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use App\Services\CmsService;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShopController extends Controller
{
    protected $productRepository;
    protected $categoryRepository;
    protected $cmsService;

    public function __construct(
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        CmsService $cmsService
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->cmsService = $cmsService;
    }

    /**
     * Display home page with featured products
     */
    public function home()
    {
        $products = $this->productRepository->all();
        $categories = $this->categoryRepository->all();
        $productsCount = count($products);

        // Take only 4 products for featured display
        $featuredProducts = array_slice(
            ProductResource::collection($products)->resolve(request()),
            0, 4
        );

        $carousels = $this->cmsService->getCarousels();

        return Inertia::render('Welcome', [
            'products' => $featuredProducts,
            'categories' => CategoryResource::collection($categories)->resolve(request()),
            'productsCount' => $productsCount,
            'carousels' => $carousels,
            'canLogin' => \Illuminate\Support\Facades\Route::has('login'),
        ]);
    }

    /**
     * Display shop page with all products (paginated)
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $categoryId = $request->get('category_id', '');
        $sort = $request->get('sort', 'latest');
        $page = $request->get('page', 1);
        $perPage = 8;

        $query = \App\Models\Product::with(['category', 'supplier'])
            ->where('is_active', true);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('supplier', fn($s) => $s->where('name', 'like', "%{$search}%"));
            });
        }

        switch ($sort) {
            case 'price_asc':
                $query->orderBy('sell_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('sell_price', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate($perPage);
        $categories = $this->categoryRepository->all();

        return Inertia::render('Customer/Shop/Index', [
            'products' => ProductResource::collection($products->items())->resolve(request()),
            'categories' => CategoryResource::collection($categories)->resolve(request()),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
                'per_page' => $products->perPage(),
            ],
            'filters' => [
                'search' => $search,
                'category_id' => $categoryId,
                'sort' => $sort,
            ],
            'title' => 'Shop',
        ]);
    }

    /**
     * Display products by category
     */
    public function category($slug)
    {
        $category = $this->categoryRepository->findBySlug($slug);
        $products = $this->productRepository->getByCategory($category->id);
        $categories = $this->categoryRepository->all();

        return Inertia::render('Customer/Shop/Category', [
            'category' => new CategoryResource($category),
            'products' => ProductResource::collection($products)->resolve(request()),
            'categories' => CategoryResource::collection($categories)->resolve(request()),
            'title' => $category->name,
        ]);
    }

    /**
     * Display product detail
     */
    public function show($id)
    {
        $product = $this->productRepository->find($id);
        $relatedProducts = $this->productRepository->getByCategory($product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4);

        return Inertia::render('Customer/Shop/Show', [
            'product' => (new ProductResource($product))->resolve(request()),
            'relatedProducts' => ProductResource::collection($relatedProducts)->resolve(request()),
            'title' => $product->name,
        ]);
    }
}
