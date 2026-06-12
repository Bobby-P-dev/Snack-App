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
            'image' => 'required|image|max:5120', // max 5MB
            'description' => 'nullable|string',
            'link_url' => 'nullable|url',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        // Upload image to S3/MinIO
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('carousel', 's3');
            $validated['image_url'] = $path;
            unset($validated['image']);
        }

        CmsCarousel::create($validated);

        // Clear cache
        $this->cmsService->clearCache('carousels');

        return redirect()->back()->with('message', 'Carousel created successfully');
    }

    /**
     * Update carousel
     */
    public function update(Request $request, CmsCarousel $cmsCarousel)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|max:5120',
            'description' => 'nullable|string',
            'link_url' => 'nullable|url',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        // Upload new image if provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($cmsCarousel->image_url) {
                Storage::disk('s3')->delete($cmsCarousel->image_url);
            }

            $path = $request->file('image')->store('carousel', 's3');
            $validated['image_url'] = $path;
            unset($validated['image']);
        }

        $cmsCarousel->update($validated);

        // Clear cache
        $this->cmsService->clearCache('carousels');

        return redirect()->back()->with('message', 'Carousel updated successfully');
    }

    /**
     * Delete carousel
     */
    public function destroy(CmsCarousel $cmsCarousel)
    {
        // Delete image from S3
        if ($cmsCarousel->image_url) {
            Storage::disk('s3')->delete($cmsCarousel->image_url);
        }

        $cmsCarousel->delete();

        // Clear cache
        $this->cmsService->clearCache('carousels');

        return redirect()->back()->with('message', 'Carousel deleted successfully');
    }
}
