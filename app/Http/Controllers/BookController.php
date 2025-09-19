<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Requests\BookRequest;
use Illuminate\Support\Facades\DB;
use App\Services\NetSuiteRestletService;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::query()
            ->latest()
            ->paginate(20);
        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        $data = $request->validated();
        $book = null;
        DB::transaction(function () use ($data, &$book) {
            $book = Book::create($data);
        });
        
        // NetSuite RESTlet sync
        $restletService = app(NetSuiteRestletService::class);
        $response = $restletService->upsertBook($book);
        $restletService->syncBookData($book, $response);
        
        return redirect()->route('books.index')->with('success', 'Book created and synced to NetSuite.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, Book $book)
    {
        $data = $request->validated();
        DB::transaction(function () use ($data, $book) {
            $book->update($data);
        });
        
        // NetSuite RESTlet sync
        $restletService = app(NetSuiteRestletService::class);
        $response = $restletService->upsertBook($book);
        $restletService->syncBookData($book, $response);
        
        return redirect()->route('books.index')->with('success', 'Book updated and synced to NetSuite.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        // NetSuite RESTlet sync
        $restletService = app(NetSuiteRestletService::class);
        $response = $restletService->deleteBook($book);
        
        DB::transaction(function () use ($book) {
            $book->delete();
        });
        
        return redirect()->route('books.index')->with('success', 'Book deleted and synced to NetSuite.');
    }

    /**
     * Bulk delete books
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return back()->with('error', 'No books selected.');
        }
        
        $restletService = app(NetSuiteRestletService::class);
        DB::transaction(function () use ($ids, $restletService) {
            $books = Book::whereIn('id', $ids)->get();
            foreach ($books as $book) {
                $restletService->deleteBook($book);
                $book->delete();
            }
        });
        
        return back()->with('success', 'Selected books deleted and synced to NetSuite.');
    }

    /**
     * Bulk import books from CSV
     */
    public function bulkImport(Request $request)
    {
        $request->validate([
            'csv' => 'required|file|mimes:csv,txt',
        ]);
        
        $path = $request->file('csv')->getRealPath();
        $rows = array_map('str_getcsv', file($path));
        $header = array_map('trim', array_shift($rows));
        
        $restletService = app(NetSuiteRestletService::class);
        DB::transaction(function () use ($rows, $header, $restletService) {
            foreach ($rows as $row) {
                $data = array_combine($header, $row);
                if (!$data) continue;
                
                $payload = [
                    'isbn' => $data['isbn'] ?? null,
                    'title' => $data['title'] ?? null,
                    'author' => $data['author'] ?? null,
                    'description' => $data['description'] ?? null,
                    'price' => (float)($data['price'] ?? 0),
                    'pages' => (int)($data['pages'] ?? 0),
                    'publisher' => $data['publisher'] ?? null,
                    'published_date' => $data['published_date'] ?? null,
                    'genre' => $data['genre'] ?? null,
                    'language' => $data['language'] ?? 'English',
                    'stock' => (int)($data['stock'] ?? 0),
                    'is_active' => isset($data['is_active']) ? (bool)$data['is_active'] : true,
                ];
                
                $book = Book::updateOrCreate(['isbn' => $payload['isbn']], $payload);
                $response = $restletService->upsertBook($book);
                $restletService->syncBookData($book, $response);
            }
        });
        
        return back()->with('success', 'Bulk import completed and synced to NetSuite.');
    }

    /**
     * Fetch books from NetSuite
     */
    public function fetchFromNetSuite(Request $request)
    {
        $filters = $request->only(['genre', 'author', 'is_active']);
        $restletService = app(NetSuiteRestletService::class);
        $response = $restletService->fetchBooks($filters);
        
        if ($response['success'] ?? false) {
            return back()->with('success', 'Books fetched from NetSuite successfully.');
        } else {
            return back()->with('error', 'Failed to fetch books from NetSuite: ' . ($response['error'] ?? 'Unknown error'));
        }
    }
}
