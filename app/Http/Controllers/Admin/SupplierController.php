<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use App\Repositories\SupplierRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupplierController extends Controller
{
    protected $supplierRepository;

    public function __construct(SupplierRepository $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;
    }

    /**
     * Display a listing of suppliers (with search & pagination)
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $perPage = 10;

        $query = Supplier::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(phone) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(address) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        $suppliers = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return Inertia::render('Admin/Supplier/Index', [
            'suppliers' => SupplierResource::collection($suppliers->items())->resolve(request()),
            'pagination' => [
                'current_page' => $suppliers->currentPage(),
                'last_page' => $suppliers->lastPage(),
                'total' => $suppliers->total(),
                'per_page' => $suppliers->perPage(),
            ],
            'filters' => ['search' => $search],
            'title' => 'Daftar Supplier',
        ]);
    }

    /**
     * Show the form for creating a new supplier
     */
    public function create()
    {
        return Inertia::render('Admin/Supplier/Create', [
            'title' => 'Tambah Supplier',
        ]);
    }

    /**
     * Store a newly created supplier in storage
     */
    public function store(StoreSupplierRequest $request)
    {
        try {
            $this->supplierRepository->create($request->validated());
            return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan supplier: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified supplier in storage
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        try {
            $this->supplierRepository->update($supplier->id, $request->validated());
            return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui supplier: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified supplier from storage
     */
    public function destroy(Supplier $supplier)
    {
        if ($supplier->products()->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus supplier yang memiliki produk');
        }

        $this->supplierRepository->delete($supplier->id);

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil dihapus');
    }
}
