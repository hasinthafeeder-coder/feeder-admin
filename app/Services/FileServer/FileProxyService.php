<?php

namespace App\Services\FileServer;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class FileProxyService
{
    public function thumbnail(string $uuid, string $size = 'md'): Response
    {
        $response = $this->client()->get("/api/files/{$uuid}/thumbnail", [
            'size' => $size,
        ]);

        return $this->toResponse($response);
    }

    public function view(string $uuid): Response
    {
        $response = $this->client()->get("/api/files/{$uuid}/view");

        return $this->toResponse($response);
    }

    public function download(string $uuid): Response
    {
        $response = $this->client()->get("/api/files/{$uuid}/download");

        return $this->toDownloadResponse($response);
    }

    private function client(): PendingRequest
    {
        return Http::baseUrl(config('feeder.file_server.url'))
            ->accept('*/*')
            ->withToken(config('feeder.file_server.api_key'));
    }

    private function toResponse(ClientResponse $response): Response
    {
        if (! $response->successful()) {
            abort($response->status());
        }

        return response($response->body(), $response->status())
            ->header('Content-Type', $this->contentType($response))
            ->header('Cache-Control', $this->cacheControl($response));
    }

    private function toDownloadResponse(ClientResponse $response): Response
    {
        if (! $response->successful()) {
            abort($response->status());
        }

        $disposition = $response->header('Content-Disposition') ?: 'attachment';

        return response($response->body(), $response->status())
            ->header('Content-Type', $this->contentType($response))
            ->header('Content-Disposition', $disposition)
            ->header('Cache-Control', 'private, no-store');
    }

    private function contentType(ClientResponse $response): string
    {
        return $response->header('Content-Type') ?: 'application/octet-stream';
    }

    private function cacheControl(ClientResponse $response): string
    {
        return $response->header('Cache-Control') ?: 'private, max-age=3600';
    }
}
