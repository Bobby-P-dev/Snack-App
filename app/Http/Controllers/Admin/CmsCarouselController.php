<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsCarousel;
use App\Services\CmsService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class CmsCarouselController extends Controller
{
    protected $cmsService;

    public function __construct(CmsService $cmsService)
    {
        $this->cmsService = $cmsService;
    }

    /**
     * Display carousel list
     */
    public function index()
    {
        $carousels = CmsCarousel::orderBy('order')->get();

        return Inertia::render('Admin/CmsCarousel/Index', [
            'carousels' => $carousels,
        ]);
    }

    /**
     * Store new carousel
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|max:5120',
            'description' => 'nullable|string',
            'link_url' => 'nullable|url',
            'order' => 'nullable|integer',
            'duration' => 'nullable|integer|min:1|max:30',
            'is_active' => 'boolean',
        ]);

        // Auto-set order to last
        if (!$request->filled('order')) {
            $maxOrder = CmsCarousel::max('order');
            $validated['order'] = ($maxOrder ?? 0) + 1;
        }

        // Upload image to S3/MinIO
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('carousel', 's3');
            $validated['image_url'] = $path;
            unset($validated['image']);
        }

        CmsCarousel::create($validated);
        $this->cmsService->clearCache('carousels');

        return redirect()->back()->with('success', 'Banner berhasil ditambahkan');
    }

    /**
     * Update carousel
     */
    public function update(Request $request, CmsCarousel $carousel)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|max:5120',
            'description' => 'nullable|string',
            'link_url' => 'nullable|url',
            'order' => 'nullable|integer',
            'duration' => 'nullable|integer|min:1|max:30',
            'is_active' => 'boolean',
        ]);

        // Upload new image if provided
        if ($request->hasFile('image')) {
            if ($carousel->image_url) {
                Storage::disk('s3')->delete($carousel->image_url);
            }
            $path = $request->file('image')->store('carousel', 's3');
            $validated['image_url'] = $path;
            unset($validated['image']);
        }

        $carousel->update($validated);
        $this->cmsService->clearCache('carousels');

        return redirect()->back()->with('success', 'Banner berhasil diperbarui');
    }

    /**
     * Reorder carousels (drag & drop)
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:cms_carousels,id',
            'items.*.order' => 'required|integer|min:1',
        ]);

        foreach ($request->items as $item) {
            CmsCarousel::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        $this->cmsService->clearCache('carousels');

        return redirect()->back()->with('success', 'Urutan banner berhasil diperbarui');
    }

    /**
     * Delete carousel
     */
    public function destroy(CmsCarousel $carousel)
    {
        if ($carousel->image_url) {
            Storage::disk('s3')->delete($carousel->image_url);
        }

        $carousel->delete();
        $this->cmsService->clearCache('carousels');

        return redirect()->back()->with('success', 'Banner berhasil dihapus');
    }
}
