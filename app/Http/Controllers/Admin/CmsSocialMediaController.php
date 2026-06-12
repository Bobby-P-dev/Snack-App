<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsSocialMedia;
use App\Services\CmsService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CmsSocialMediaController extends Controller
{
    protected $cmsService;

    public function __construct(CmsService $cmsService)
    {
        $this->cmsService = $cmsService;
    }

    /**
     * Display social media list
     */
    public function index()
    {
        $socials = CmsSocialMedia::orderBy('order')->get();

        return Inertia::render('Admin/CmsSocialMedia/Index', [
            'socials' => $socials,
        ]);
    }

    /**
     * Store new social media
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'platform' => 'required|string|max:255',
            'url' => 'required|url',
            'icon_name' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        CmsSocialMedia::create($validated);

        // Clear cache
        $this->cmsService->clearCache('social_media');

        return redirect()->back()->with('message', 'Social Media created successfully');
    }

    /**
     * Update social media
     */
    public function update(Request $request, CmsSocialMedia $cmsSocialMedia)
    {
        $validated = $request->validate([
            'platform' => 'required|string|max:255',
            'url' => 'required|url',
            'icon_name' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $cmsSocialMedia->update($validated);

        // Clear cache
        $this->cmsService->clearCache('social_media');

        return redirect()->back()->with('message', 'Social Media updated successfully');
    }

    /**
     * Delete social media
     */
    public function destroy(CmsSocialMedia $cmsSocialMedia)
    {
        $cmsSocialMedia->delete();

        // Clear cache
        $this->cmsService->clearCache('social_media');

        return redirect()->back()->with('message', 'Social Media deleted successfully');
    }
}
