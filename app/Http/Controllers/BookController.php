<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    public function getAllBooks(Request $request) {
        $title = $request->get('title', '');
        $author = $request->get('author', '');
        $page = $request->get('page', 1);
        $pageSize = $request->get('page-size', 50);

        $books = Book::where('title', 'like', "%$title%")
            ->where('author', 'like', "%$author%")
            ->take($pageSize)
            ->skip(($page - 1) * $pageSize)
            ->get();
        
        return response()->json(['books' => $books]);
    }

    public function getById(string $id): JsonResponse {
        // $book = Book::where('id', $id)->get();
        // $book = Book::where('id', $id)->first();
        $book = Book::find($id);

        if(empty($book)) {
            return new JsonResponse(
                null,
                JsonResponse:: HTTP_NOT_FOUND
            );
        }

        return response()->json(['book' => $book]);
    }
}
