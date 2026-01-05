<?php

namespace App\Http\Controllers;

use App\Repositories\Interface\BookRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Requests\Book\BookListRequest;
use App\Http\Requests\Book\FormBookRequest;
use App\Constant\PerPage;

class BookController extends Controller
{
    public function __construct(
        private readonly BookRepositoryInterface $bookRepository,
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

        return $this->successResponse($books);
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
