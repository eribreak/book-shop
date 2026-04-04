<?php

namespace App\Http\Controllers;

use App\Repositories\Interface\BookReportRepositoryInterface;
use App\Repositories\Interface\BookRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Requests\Book\BookListRequest;
use App\Http\Requests\Book\FormBookRequest;
use App\Constant\PerPage;

class BookController extends Controller
{
    public function __construct(
        private readonly BookRepositoryInterface $bookRepository,
        private readonly BookReportRepositoryInterface $bookReportRepository,
    ) {}

    public function getList(BookListRequest $request)
    {
        $perPage = (int) $request->query('per_page', PerPage::DEFAULT);
        $filters = [
            'q' => $request->query('q', ''),
            'category_id' => $request->query('category_id'),
            'author_id' => $request->query('author_id'),
            'publisher_id' => $request->query('publisher_id'),
        ];

        $books = $this->bookRepository->getBooks($filters, $perPage);
        $books->appends($request->query());

        return response()->json($books);
    }

    public function borrowedMonthly(Request $request)
    {
        $year = (int) $request->query('year', now()->year);

        return response()->json([
            'year' => $year,
            'data' => $this->bookReportRepository->getBorrowedQuantityByMonth($year),
        ]);
    }

    public function topBorrowers(Request $request)
    {
        $limit = (int) $request->query('limit', 10);

        return response()->json([
            'limit' => max(1, min($limit, 100)),
            'data' => $this->bookReportRepository->getTopBorrowers($limit),
        ]);
    }

    public function topBorrowedBooks(Request $request)
    {
        $limit = (int) $request->query('limit', 10);

        return response()->json([
            'limit' => max(1, min($limit, 100)),
            'data' => $this->bookReportRepository->getTopBorrowedBooks($limit),
        ]);
    }

    public function booksCountByCategory()
    {
        return response()->json([
            'data' => $this->bookReportRepository->getBooksCountByCategory(),
        ]);
    }

    public function topWishlistedBooks(Request $request)
    {
        $limit = (int) $request->query('limit', 30);

        return response()->json([
            'limit' => max(1, min($limit, 100)),
            'data' => $this->bookReportRepository->getTopWishlistedBooks($limit),
        ]);
    }

    public function getDetail(int $id)
    {
        $book = $this->bookRepository->getDetail($id);

        if (!$book) {
            return $this->errorResponse('Book not found.');
        }

        return $this->successResponse($book);
    }

    public function create(FormBookRequest $request)
    {
        $data = $request->validated();
        $book = $this->bookRepository->create($data);

        return $this->successResponse($book, 'Book created successfully.');
    }

    public function update(int $id, FormBookRequest $request)
    {
        $data = $request->validated();
        $book = $this->bookRepository->update($id, $data);

        if (!$book) {
            return $this->errorResponse('Book not found.');
        }

        return $this->successResponse($book, 'Book updated successfully.');
    }
    public function delete(int $id)
    {
        $deleted = $this->bookRepository->delete($id);

        if (!$deleted) {
            return $this->errorResponse('Book not found.');
        }

        return $this->successResponse('Book deleted successfully.');
    }
}
