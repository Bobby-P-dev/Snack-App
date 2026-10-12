<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\SnackBoxPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CustomerPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_page_is_accessible_and_renders_correct_component(): void
    {
        Category::create(['name' => 'Kue Basah', 'slug' => 'kue-basah']);
        SnackBoxPackage::create([
            'name' => 'Paket 3 Kue',
            'slug' => 'paket-3-kue',
            'capacity' => 3,
            'box_price' => 2000,
            'is_active' => true,
        ]);

        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Customer/About')
            ->has('categories')
            ->has('snackBoxPackages')
        );
    }

    public function test_tentang_kami_redirects_to_about(): void
    {
        $response = $this->get('/tentang-kami');

        $response->assertRedirect('/about');
    }

    public function test_contact_page_is_accessible_and_renders_correct_component(): void
    {
        $response = $this->get('/contact');

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Customer/Contact')
            ->has('deliveryAreas')
        );
    }

    public function test_kontak_redirects_to_contact(): void
    {
        $response = $this->get('/kontak');

        $response->assertRedirect('/contact');
    }
}
