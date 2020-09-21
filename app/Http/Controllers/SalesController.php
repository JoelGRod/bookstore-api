<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Sale;
use App\Models\SalesBook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SalesController extends Controller
{
    public function newSale(Request $request) {
        $books = json_decode($request->get('books'), true);

        if(empty($books) | !is_array($books)) {
            return new JsonResponse(
                ['error' => 'There are no books in the array'],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $bookObjects = [];
        $saleBooks = [];

        // Check books availability
        foreach($books as $bookId => $amount) {
            $book = Book::find($bookId);
            if(empty($book) | $book->stock < $amount) {
                return new JsonResponse(
                    ['error' => "Not available or not valid book: $bookId"],
                    JsonResponse::HTTP_BAD_REQUEST
                );
            }
            $bookObjects[] = $book;
            $saleBooks[] = [
                'book_id' => $bookId,
                'amount' => $amount
            ];
        }

        // Sale creation
        $sale = Sale::create([
            'customer_id' => Auth::id()
        ]);
        
        // Book stock update in DB and sale books creation
        foreach($bookObjects as $key => $book) {
            $book->stock -= $saleBooks[$key]['amount'];
            $book->save();
            $saleBooks[$key]['sale_id'] = $sale->id;
            SalesBook::create($saleBooks[$key]);
        }

        $sale->books = $sale->salesBooks()->select('book_id', 'amount')->getResults();
        return response()->json(['sale' => $sale]);
    }

    public function getSalesByUser(Request $request): JsonResponse {
        $from = $request->get('from', '1970-01-01');
        $page = $request->get('page', 1);
        $pageSize = $request->get('page-size', 50);

        $sales = Sale::where('customer_id', Auth::id())
            ->where('created_at', '>=', $from)
            ->take($pageSize)
            ->skip(($page - 1) * $pageSize)
            ->get();

        foreach($sales as $sale) {
            $sale->books = $sale->salesBooks()->select('book_id', 'amount')->getResults();
        }

        return response()->json(['sales' => $sales]);
    }

    public function getSaleById(string $id) {
        $sale = Sale::find($id);

        if(empty($sale)) {
            return new JsonResponse(
                ['error' => 'Sale not found.'],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $sale->books = $sale->salesBooks()->select('book_id', 'amount')->getResults();
        return response()->json(['sale' => $sale]);
    }
}
