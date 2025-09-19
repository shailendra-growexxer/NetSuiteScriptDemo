<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NetSuiteRestletService
{
    private $restletUrl;
    private $scriptId;
    private $deployId;

    public function __construct()
    {
        $this->restletUrl = config('netsuite.restlet_url');
        $this->scriptId = config('netsuite.script_id');
        $this->deployId = config('netsuite.deploy_id');
    }

    /**
     * Create or update a book in NetSuite via RESTlet
     */
    public function upsertBook(Book $book): array
    {
        $payload = [
            'action' => 'upsert',
            'data' => [
                'isbn' => $book->isbn,
                'title' => $book->title,
                'author' => $book->author,
                'description' => $book->description,
                'price' => $book->price,
                'pages' => $book->pages,
                'publisher' => $book->publisher,
                'published_date' => $book->published_date?->format('Y-m-d'),
                'genre' => $book->genre,
                'language' => $book->language,
                'stock' => $book->stock,
                'is_active' => $book->is_active,
                'local_id' => $book->id,
            ]
        ];

        return $this->callRestlet($payload);
    }

    /**
     * Delete a book from NetSuite via RESTlet
     */
    public function deleteBook(Book $book): array
    {
        $payload = [
            'action' => 'delete',
            'data' => [
                'netsuite_item_id' => $book->netsuite_item_id,
                'isbn' => $book->isbn,
                'local_id' => $book->id,
            ]
        ];

        return $this->callRestlet($payload);
    }

    /**
     * Fetch books from NetSuite via RESTlet
     */
    public function fetchBooks(array $filters = []): array
    {
        $payload = [
            'action' => 'fetch',
            'data' => $filters
        ];

        return $this->callRestlet($payload);
    }

    /**
     * Call NetSuite RESTlet
     */
    private function callRestlet(array $payload): array
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getAuthToken(),
            ])->post($this->restletUrl, $payload);

            if ($response->successful()) {
                $result = $response->json();
                Log::info('NetSuite RESTlet call successful', ['payload' => $payload, 'response' => $result]);
                return $result;
            } else {
                Log::error('NetSuite RESTlet call failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'payload' => $payload
                ]);
                return ['success' => false, 'error' => 'RESTlet call failed: ' . $response->status()];
            }
        } catch (\Exception $e) {
            Log::error('NetSuite RESTlet exception', ['error' => $e->getMessage(), 'payload' => $payload]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get authentication token (implement based on your NetSuite auth method)
     */
    private function getAuthToken(): string
    {
        // This is a placeholder - implement based on your NetSuite authentication
        // Could be OAuth 2.0, Token-Based Authentication, etc.
        return config('netsuite.auth_token', 'demo-token');
    }

    /**
     * Sync book data after successful NetSuite operation
     */
    public function syncBookData(Book $book, array $netsuiteResponse): void
    {
        if (isset($netsuiteResponse['success']) && $netsuiteResponse['success']) {
            $book->update([
                'netsuite_item_id' => $netsuiteResponse['netsuite_item_id'] ?? $book->netsuite_item_id,
                'synced_at' => now(),
            ]);
        }
    }
}
