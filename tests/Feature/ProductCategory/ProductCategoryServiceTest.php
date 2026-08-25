<?php

namespace Tests\Feature\ProductCategory;

use App\Models\User;
use App\Services\Product\ProductCategoryService;
use Feeder\Core\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ProductCategoryServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_root_category_with_audit_fields(): void
    {
        $admin = $this->makeUser();
        Auth::login($admin);

        $service = app(ProductCategoryService::class);

        $category = $service->create([
            'name' => 'Office Supplies',
            'description' => 'Daily office essentials.',
            'sort_order' => 7,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('product_categories', [
            'id' => $category->id,
            'name' => 'Office Supplies',
            'slug' => 'office-supplies',
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
            'is_active' => true,
        ]);
    }

    public function test_it_rejects_self_parenting_on_update(): void
    {
        $admin = $this->makeUser();
        Auth::login($admin);

        $category = ProductCategory::query()->create([
            'id' => '11111111-1111-1111-1111-111111111111',
            'name' => 'Furniture',
            'slug' => 'furniture',
            'sort_order' => 1,
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        $this->expectException(ValidationException::class);

        app(ProductCategoryService::class)->update($category, [
            'name' => 'Furniture',
            'parent_id' => $category->id,
        ]);
    }

    public function test_it_rejects_deleting_categories_with_children(): void
    {
        $admin = $this->makeUser();
        Auth::login($admin);

        $parent = ProductCategory::query()->create([
            'id' => '22222222-2222-2222-2222-222222222222',
            'name' => 'Home',
            'slug' => 'home',
            'sort_order' => 1,
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        ProductCategory::query()->create([
            'id' => '33333333-3333-3333-3333-333333333333',
            'parent_id' => $parent->id,
            'name' => 'Kitchen',
            'slug' => 'kitchen',
            'sort_order' => 1,
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        $this->expectException(ValidationException::class);

        app(ProductCategoryService::class)->delete($parent);
    }

    public function test_it_toggles_a_category_status(): void
    {
        $admin = $this->makeUser();
        Auth::login($admin);

        $category = ProductCategory::query()->create([
            'id' => '44444444-4444-4444-4444-444444444444',
            'name' => 'Travel Gear',
            'slug' => 'travel-gear',
            'sort_order' => 1,
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        $updated = app(ProductCategoryService::class)->deactivate($category);

        $this->assertFalse((bool) $updated->is_active);
        $this->assertDatabaseHas('product_categories', [
            'id' => $category->id,
            'is_active' => false,
            'updated_by' => $admin->id,
        ]);
    }

    private function makeUser(): User
    {
        return User::query()->create([
            'id' => 1001,
            'uuid' => 'admin-user-uuid',
            'email' => 'admin@example.com',
            'password' => bcrypt('secret123'),
        ]);
    }
}
