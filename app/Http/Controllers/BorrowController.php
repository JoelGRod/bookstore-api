<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowedBook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BorrowController extends Controller
{
    public function borrowBook(Request $request): JsonResponse {
        $bookId = $request->get('book-id');

        if(empty($bookId)) {
            return new JsonResponse(
                ['error' => 'Expecting book-id parameter.'],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $book = Book::find($bookId);

        if(empty($book)) {
            return new JsonResponse(
                ['error' => 'Book not found'],
                JsonResponse::HTTP_BAD_REQUEST
            );
        } else if($book->stock < 1) {
            return new JsonResponse(
                ['error' => 'No stock'],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $book->stock--;
        $book->save();

        $borrowedBook = BorrowedBook::create(
            [
                'book_id' => $bookId,
                'customer_id' => Auth::id(),
                'start' => date('Y-m-d H:i:s')
            ]
        );

        return response()->json(['borrowed-book' => $borrowedBook]);
    }

    public function getAllBorrowedBooks(Request $request): JsonResponse {
        $from = $request->get('from', '1970-01-01');
        $page = $request->get('page', 1);
        $pageSize = $request->get('page-size', 50);

        $borrowedBooks = BorrowedBook::where('customer_id', '=', Auth::id())
            ->where('start', '>=', $from)
            ->take($pageSize)
            ->skip(($page - 1) * $pageSize)
            ->get();
        
        if(empty($borrowedBooks)) {
            return new JsonResponse(
                null,
                JsonResponse::HTTP_NOT_FOUND
            );
        }

        return response()->json(['borrowed' => $borrowedBooks]);
    }

    public function returnBook(int $id) {
        $borrowedBook = BorrowedBook::find($id);

        if(empty($borrowedBook)) {
            return new JsonResponse(
                ['error' => 'Borrowed book not found.'],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $book = Book::find($borrowedBook->book_id);
        $book->stock++;
        $book->save();

        $borrowedBook->end = date('Y-m-d H:i:s');
        $borrowedBook->save();

        return response()->json(['borrowed-book' => $borrowedBook]);
    }
}
