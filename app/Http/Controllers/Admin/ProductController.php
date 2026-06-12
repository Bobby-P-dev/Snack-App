<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\SupplierRepository;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    protected $productService;
    protected $productRepository;
    protected $categoryRepository;
    protected $supplierRepository;

    public function __construct(
        ProductService $productService,
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        SupplierRepository $supplierRepository
    ) {
        $this->productService = $productService;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->supplierRepository = $supplierRepository;
    }

    /**
     * Display a listing of products (with search & filter)
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $categoryId = $request->get('category_id', '');
        $supplierId = $request->get('supplier_id', '');

        $query = Product::with(['category', 'supplier']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('supplier', fn($s) => $s->where('name', 'like', "%{$search}%"));
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($supplierId) {
            $query->where('supplier_id', $supplierId);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15);

        return Inertia::render('Admin/Product/Index', [
            'products' => ProductResource::collection($products->items()),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
                'per_page' => $products->perPage(),
            ],
            'filters' => ['search' => $search, 'category_id' => $categoryId, 'supplier_id' => $supplierId],
            'categories' => $this->categoryRepository->all(),
            'suppliers' => $this->supplierRepository->all(),
        ]);
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'base_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:5120',
            'is_active' => 'boolean',
        ]);

        $this->productService->createProduct($validated, $request->file('image'));

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan');
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'base_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:5120',
            'is_active' => 'boolean',
        ]);

        $this->productService->updateProduct($product->id, $validated, $request->file('image'));

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui');
    }

    /**
     * Remove the specified product
     */
    public function destroy(Product $product)
    {
        $this->productService->deleteProduct($product->id);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus');
    }

    /**
     * Toggle product active status
     */
    public function toggleStatus(Product $product)
    {
        $this->productService->toggleProductStatus($product->id);

        $status = $product->fresh()->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Produk berhasil {$status}");
    }
}
