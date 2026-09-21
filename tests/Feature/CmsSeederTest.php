<?php

namespace Tests\Feature;

use App\Models\CmsCarousel;
use App\Models\CmsSetting;
use App\Models\CmsSocialMedia;
use App\Models\SnackBoxPackage;
use App\Services\CmsService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_populates_cms_and_snackbox_data(): void
    {
        // Run full database seeder
        $this->seed(DatabaseSeeder::class);

        // 1. Verify CMS Settings
        $expectedKeys = [
            'company_name',
            'company_tagline',
            'company_description',
            'badge',
            'hero_title_1',
            'hero_title_2',
            'hero_subtitle',
            'propotition1',
            'propotition2',
            'propotition3',
            'contact_email',
            'contact_phone',
            'contact_phone_display',
            'contact_address',
            'company_address',
            'operating_hours',
            'maps_embed_url',
            'dp_percentage',
            'min_order_box',
            'min_order_satuan',
            'social_instagram',
            'social_facebook',
            'social_tiktok',
            'wa_checkout_template',
            'wa_consultation_message',
            'wa_tracking_help_message',
            'wa_tracking_not_found_message',
        ];

        foreach ($expectedKeys as $key) {
            $this->assertDatabaseHas('cms_settings', ['key' => $key]);
        }

        $this->assertEquals('Padu Kue', CmsSetting::where('key', 'company_name')->value('value'));
        $this->assertEquals('halo@padukue.store', CmsSetting::where('key', 'contact_email')->value('value'));

        // 2. Verify Carousels
        $carousels = CmsCarousel::where('is_active', true)->orderBy('order')->get();
        $this->assertCount(4, $carousels);
        $this->assertEquals('Custom Snack Box Eksklusif', $carousels->first()->title);
        $this->assertEquals(5, $carousels->first()->duration);

        // 3. Verify Social Media
        $socials = CmsSocialMedia::where('is_active', true)->orderBy('order')->get();
        $this->assertCount(4, $socials);
        $this->assertTrue($socials->contains('platform', 'Instagram'));
        $this->assertTrue($socials->contains('platform', 'WhatsApp'));

        // 4. Verify Snack Box Packages
        $packages = SnackBoxPackage::where('is_active', true)->orderBy('capacity')->get();
        $this->assertCount(3, $packages);
        $this->assertEquals(3, $packages->first()->capacity);

        // 5. Verify CmsService retrieves data properly
        $cmsService = app(CmsService::class);
        $this->assertEquals('Padu Kue', $cmsService->getSetting('company_name'));
        $this->assertCount(4, $cmsService->getCarousels());
        $this->assertCount(4, $cmsService->getSocialMedia());
    }
}
