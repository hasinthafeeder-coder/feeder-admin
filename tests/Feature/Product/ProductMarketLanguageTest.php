<?php

namespace Tests\Feature\Product;

use Feeder\Core\Services\ProductMarketLanguageService;
use Tests\Support\SetsUpMarketData;
use Tests\Support\UsesMysqlTestDatabase;
use Tests\TestCase;

class ProductMarketLanguageTest extends TestCase
{
    use SetsUpMarketData;
    use UsesMysqlTestDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpMysqlTestDatabase();
        $this->seedMarketLookups();
    }

    protected function tearDown(): void
    {
        $this->tearDownMysqlTestDatabase();

        parent::tearDown();
    }

    public function test_lk_product_shows_english_sinhala_tamil(): void
    {
        $service = app(ProductMarketLanguageService::class);
        $codes = $service->languageCodesForMarket('lk');

        $this->assertSame(['en', 'si', 'ta'], $codes);
    }

    public function test_my_product_shows_english_malay_tamil(): void
    {
        $service = app(ProductMarketLanguageService::class);
        $codes = $service->languageCodesForMarket('my');

        $this->assertSame(['en', 'ms', 'ta'], $codes);
        $this->assertNotContains('si', $codes);
    }

    public function test_my_validation_accepts_malay_description(): void
    {
        $service = app(ProductMarketLanguageService::class);

        $normalized = $service->normalizeDescriptionsForMarket('my', [
            'en' => 'English text',
            'ms' => 'Teks Melayu',
            'ta' => 'தமிழ்',
        ]);

        $this->assertCount(3, $normalized);
        $this->assertSame('ms', $normalized[1]['language_code']);
    }

    public function test_my_product_rejects_sinhala_description_field(): void
    {
        $service = app(ProductMarketLanguageService::class);

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $service->assertDescriptionsMatchMarket('my', [
            'en' => 'English',
            'si' => 'Should not be allowed',
        ]);
    }

    public function test_unknown_market_falls_back_to_english_only(): void
    {
        $service = app(ProductMarketLanguageService::class);
        $codes = $service->languageCodesForMarket('th');

        $this->assertSame(['en'], $codes);
    }
}
