<?php

namespace App\Http\Controllers;

use App\Repositories\Interface\BookRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Requests\Book\BookListRequest;

class BookController extends Controller
{
    public function __construct(
        private readonly BookRepositoryInterface $bookRepository,
    ) {}

    public function getBooks(BookListRequest $request)
    {
        $perPage = (int) $request->query('per_page', 16);
        $filters = [
            'q' => $request->query('q', ''),
            'category_id' => $request->query('category_id'),
            'author_id' => $request->query('author_id'),
            'publisher_id' => $request->query('publisher_id'),
        ];

        $books = $this->bookRepository->getBooks($filters, $perPage);
        $books->appends($request->query());

        return $this->successResponse($books);
    }
}
