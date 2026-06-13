<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of categories (with search & pagination)
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $perPage = 10;

        $query = Category::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $categories = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return Inertia::render('Admin/Category/Index', [
            'categories' => CategoryResource::collection($categories->items())->resolve(request()),
            'pagination' => [
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'total' => $categories->total(),
                'per_page' => $categories->perPage(),
            ],
            'filters' => ['search' => $search],
        ]);
    }

    /**
     * Store a newly created category
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $this->categoryRepository->create($request->validated());
            return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan kategori: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, Category $category)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            ]);

            $this->categoryRepository->update($category->id, $validated);

            return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput()->with('error', 'Validasi gagal, periksa kembali input Anda');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui kategori: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified category
     */
    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus kategori yang memiliki produk');
        }

        $this->categoryRepository->delete($category->id);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus');
    }
}
