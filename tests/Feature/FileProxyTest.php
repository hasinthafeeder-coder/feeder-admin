<?php

namespace Tests\Feature;

use App\Models\User;
use Feeder\Core\Services\Order\OrderPaymentReviewService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Mockery;
use Tests\TestCase;

class FileProxyTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->mock(OrderPaymentReviewService::class, function ($mock): void {
            $mock->shouldReceive('authorizeFileAccessIfPaymentProof')->andReturnNull();
        });
    }

    public function test_thumbnail_route_proxies_remote_image_response(): void
    {
        Http::fake([
            '127.0.0.1:8000/api/files/DWTJRJKUGP/thumbnail*' => Http::response(
                'binary-image',
                200,
                [
                    'Content-Type' => 'image/jpeg',
                    'Cache-Control' => 'public, max-age=31536000',
                ]
            ),
        ]);

        $user = new User;
        $user->forceFill([
            'id' => 1,
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/files/DWTJRJKUGP/thumbnail/sm');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'image/jpeg');
        $response->assertSee('binary-image', false);

        Http::assertSent(function (Request $request): bool {
            return $request->hasHeader('Authorization', 'Bearer '.config('feeder.file_server.api_key'))
                && str_contains($request->url(), '/api/files/DWTJRJKUGP/thumbnail');
        });
    }

    public function test_view_route_proxies_remote_file_response(): void
    {
        Http::fake([
            '127.0.0.1:8000/api/files/DWTJRJKUGP/view' => Http::response(
                'binary-pdf',
                200,
                [
                    'Content-Type' => 'application/pdf',
                    'Cache-Control' => 'private, max-age=3600',
                ]
            ),
        ]);

        $user = new User;
        $user->forceFill([
            'id' => 1,
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/files/DWTJRJKUGP/view');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertSee('binary-pdf', false);
    }

    public function test_download_route_proxies_remote_file_response(): void
    {
        Http::fake([
            '127.0.0.1:8000/api/files/DWTJRJKUGP/download' => Http::response(
                'binary-pdf-download',
                200,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="slip.pdf"',
                ]
            ),
        ]);

        $user = new User;
        $user->forceFill([
            'id' => 1,
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/files/DWTJRJKUGP/download');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition', 'attachment; filename="slip.pdf"');
        $response->assertSee('binary-pdf-download', false);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
