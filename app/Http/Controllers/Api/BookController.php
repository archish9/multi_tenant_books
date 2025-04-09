<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();
        
        return response()->json([
            'success' => true,
            'data' => $books->map(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'description' => $book->description,
                    'published_date' => $book->published_date,
                    'isbn' => $book->isbn,
                    'cover' => $book->getFirstMediaUrl('cover'),
                    'created_at' => $book->created_at,
                    'updated_at' => $book->updated_at,
                ];
            }),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'published_date' => 'nullable|date',
            'isbn' => 'nullable|string|max:255',
            'cover' => 'nullable|image|max:2048',
        ]);

        $book = Book::create([
            'title' => $validated['title'],
            'author' => $validated['author'],
            'description' => $validated['description'] ?? null,
            'published_date' => $validated['published_date'] ?? null,
            'isbn' => $validated['isbn'] ?? null,
        ]);

        if ($request->hasFile('cover')) {
            $book->addMediaFromRequest('cover')->toMediaCollection('cover');
        }

        return response()->json([
            'success' => true,
            'message' => 'Book created successfully',
            'data' => [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'description' => $book->description,
                'published_date' => $book->published_date,
                'isbn' => $book->isbn,
                'cover' => $book->getFirstMediaUrl('cover'),
                'created_at' => $book->created_at,
                'updated_at' => $book->updated_at,
            ],
        ], 201);
    }
    public function show($id)
    {
        $book = Book::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'description' => $book->description,
                'published_date' => $book->published_date,
                'isbn' => $book->isbn,
                'cover' => $book->getFirstMediaUrl('cover'),
                'created_at' => $book->created_at,
                'updated_at' => $book->updated_at,
            ],
        ]);
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'published_date' => 'nullable|date',
            'isbn' => 'nullable|string|max:255',
            'cover' => 'nullable|image|max:2048',
        ]);

        $book->update([
            'title' => $validated['title'] ?? $book->title,
            'author' => $validated['author'] ?? $book->author,
            'description' => $validated['description'] ?? $book->description,
            'published_date' => $validated['published_date'] ?? $book->published_date,
            'isbn' => $validated['isbn'] ?? $book->isbn,
        ]);

        if ($request->hasFile('cover')) {
            $book->clearMediaCollection('cover');
            $book->addMediaFromRequest('cover')->toMediaCollection('cover');
        }

        return response()->json([
            'success' => true,
            'message' => 'Book updated successfully',
            'data' => [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'description' => $book->description,
                'published_date' => $book->published_date,
                'isbn' => $book->isbn,
                'cover' => $book->getFirstMediaUrl('cover'),
                'created_at' => $book->created_at,
                'updated_at' => $book->updated_at,
            ],
        ]);
    }
    public function destroy($id)
    {
        $book = Book::findOrFail($id);        
        $book->clearMediaCollection('cover');
        $book->delete();

        return response()->json([
            'success' => true,
            'message' => 'Book deleted successfully',
        ]);
    }
}
