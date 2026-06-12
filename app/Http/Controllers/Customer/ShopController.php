<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use App\Services\CmsService;
use App\Models\Category;
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
     * Display shop page with all products
     */
    public function index()
    {
        $products = $this->productRepository->all();
        $categories = $this->categoryRepository->all();

        return Inertia::render('Customer/Shop/Index', [
            'products' => ProductResource::collection($products)->resolve(request()),
            'categories' => CategoryResource::collection($categories)->resolve(request()),
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
            'product' => new ProductResource($product),
            'relatedProducts' => ProductResource::collection($relatedProducts)->resolve(request()),
            'title' => $product->name,
        ]);
    }
}
