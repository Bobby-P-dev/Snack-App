<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsSetting;
use App\Services\CmsService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CmsSettingController extends Controller
{
    protected $cmsService;

    public function __construct(CmsService $cmsService)
    {
        $this->cmsService = $cmsService;
    }

    /**
     * Display CMS settings page
     */
    public function index()
    {
        $settings = CmsSetting::all();

        return Inertia::render('Admin/CmsSettings/Index', [
            'settings' => $settings,
        ]);
    }

    /**
     * Update setting
     */
    public function update(Request $request, CmsSetting $cmsSetting)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:cms_settings,key,' . $cmsSetting->id,
            'value' => 'required',
            'type' => 'required|in:text,textarea,image,json,number,email,phone,url',
            'description' => 'nullable|string',
        ]);

        $cmsSetting->update($validated);

        // Clear cache
        $this->cmsService->clearCache($cmsSetting->key);

        return redirect()->back()->with('message', 'Setting updated successfully');
    }

    /**
     * Create or update setting
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:cms_settings',
            'value' => 'required',
            'type' => 'required|in:text,textarea,image,json,number,email,phone,url',
            'description' => 'nullable|string',
        ]);

        CmsSetting::create($validated);

        // Clear cache
        $this->cmsService->clearCache($validated['key']);

        return redirect()->back()->with('message', 'Setting created successfully');
    }
}
