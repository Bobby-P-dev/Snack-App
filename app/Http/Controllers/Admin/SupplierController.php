<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use App\Repositories\SupplierRepository;
use Inertia\Inertia;

class SupplierController extends Controller
{
    protected $supplierRepository;

    public function __construct(SupplierRepository $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;
    }

    /**
     * Display a listing of suppliers
     */
    public function index()
    {
        $suppliers = $this->supplierRepository->paginate(15);

        return Inertia::render('Admin/Supplier/Index', [
            'suppliers' => SupplierResource::collection($suppliers->items()),
            'pagination' => [
                'current_page' => $suppliers->currentPage(),
                'last_page' => $suppliers->lastPage(),
                'total' => $suppliers->total(),
                'per_page' => $suppliers->perPage(),
            ],
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
        $supplier = $this->supplierRepository->create($request->validated());

        return redirect()->route('admin.suppliers.show', $supplier)->with('success', 'Supplier berhasil ditambahkan');
    }

    /**
     * Display the specified supplier
     */
    public function show(Supplier $supplier)
    {
        return Inertia::render('Admin/Supplier/Show', [
            'supplier' => new SupplierResource($supplier),
            'title' => $supplier->name,
        ]);
    }

    /**
     * Show the form for editing the specified supplier
     */
    public function edit(Supplier $supplier)
    {
        return Inertia::render('Admin/Supplier/Edit', [
            'supplier' => new SupplierResource($supplier),
            'title' => 'Edit Supplier: ' . $supplier->name,
        ]);
    }

    /**
     * Update the specified supplier in storage
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $updatedSupplier = $this->supplierRepository->update($supplier->id, $request->validated());

        return redirect()->route('admin.suppliers.show', $updatedSupplier)->with('success', 'Supplier berhasil diperbarui');
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
