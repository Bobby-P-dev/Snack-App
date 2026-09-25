<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use App\Services\CmsService;
use App\Models\Category;
use App\Models\SnackBoxPackage;
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
     * Display home page with featured products and snack box packages
     */
    public function home()
    {
        $products = $this->productRepository->all();
        $categories = $this->categoryRepository->all();
        $productsCount = count($products);

        // Take up to 6 products for featured display
        $featuredProducts = array_slice(
            ProductResource::collection($products)->resolve(request()),
            0, 6
        );

        $snackBoxPackages = SnackBoxPackage::where('is_active', true)
            ->orderBy('capacity')
            ->get();

        $carousels = $this->cmsService->getCarousels();

        return Inertia::render('Welcome', [
            'products' => $featuredProducts,
            'snackBoxPackages' => $snackBoxPackages,
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
        $priceRange = $request->get('price_range', 'all');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $sort = $request->get('sort', 'latest');
        $page = $request->get('page', 1);
        $perPage = 8;

        $query = \App\Models\Product::with(['category', 'supplier'])
            ->where('is_active', true);

        // Filter Category
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Filter Search (Product name or supplier)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('supplier', fn($s) => $s->where('name', 'like', "%{$search}%"));
            });
        }

        // Filter Price Range
        if ($priceRange === 'under_10k') {
            $query->where('sell_price', '<', 10000);
        } elseif ($priceRange === '10k_25k') {
            $query->whereBetween('sell_price', [10000, 25000]);
        } elseif ($priceRange === 'above_25k') {
            $query->where('sell_price', '>', 25000);
        } elseif ($priceRange === 'custom' || $request->filled('min_price') || $request->filled('max_price')) {
            if ($request->filled('min_price') && is_numeric($minPrice)) {
                $query->where('sell_price', '>=', (float) $minPrice);
            }
            if ($request->filled('max_price') && is_numeric($maxPrice)) {
                $query->where('sell_price', '<=', (float) $maxPrice);
            }
        }

        // Sorting
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('sell_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('sell_price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate($perPage)->withQueryString();
        $categories = Category::withCount(['products' => fn ($q) => $q->where('is_active', true)])->get();

        return Inertia::render('Customer/Shop/Index', [
            'products' => ProductResource::collection($products->items())->resolve(request()),
            'categories' => CategoryResource::collection($categories)->resolve(request()),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
            ],
            'filters' => [
                'search' => $search,
                'category_id' => $categoryId,
                'price_range' => $priceRange,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'sort' => $sort,
            ],
            'title' => 'Kue Satuan',
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
