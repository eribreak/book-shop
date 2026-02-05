<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Author\FormAuthorRequest;

class AuthorController extends Controller
{
    public function __construct(
        private readonly \App\Repositories\Interface\AuthorRepositoryInterface $authorRepository,
    ) {}

    public function getList(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $filters = [
            'q' => $request->query('q', ''),
        ];

        $authors = $this->authorRepository->getAuthors($filters, $perPage);
        $authors->appends($request->query());

        return $this->successResponse($authors);
    }

    public function getDetail(int $id)
    {
        $author = $this->authorRepository->getDetail($id);

        if (!$author) {
            return $this->errorResponse('Author not found.');
        }

        return $this->successResponse($author);
    }

    public function create(FormAuthorRequest $request)
    {
        $data = $request->validated();

        $author = $this->authorRepository->create($data);
        return $this->successResponse($author, 'Author created successfully.', 201);
    }

    public function update(int $id, FormAuthorRequest $request)
    {
        $data = $request->validated();

        $author = $this->authorRepository->update($id, $data);
        if (!$author) {
            return $this->errorResponse('Author not found.');
        }

        return $this->successResponse($author, 'Author updated successfully.');
    }

    public function delete(int $id)
    {
        $author = $this->authorRepository->getDetail($id);

        if (!$author) {
            return $this->errorResponse('Author not found.');
        }

        if ($author->books->isNotEmpty()) {
            return $this->errorResponse('Cannot delete author with associated books.');
        }

        $deleted = $this->authorRepository->delete($id);

        if (!$deleted) {
            return $this->errorResponse('Failed to delete author.');
        }

        return $this->successResponse('Author deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids) || !is_array($ids)) {
            return $this->errorResponse('Invalid author IDs.');
        }

        $deletedCount = $this->authorRepository->bulkDelete($ids);

        return $this->successResponse("Deleted {$deletedCount} authors successfully.");
    }
}
