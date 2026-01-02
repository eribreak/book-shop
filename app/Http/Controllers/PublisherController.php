<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Publihser\FormPublisherRequest;

class PublisherController extends Controller
{
    public function __construct(
        private readonly \App\Repositories\Interface\PublisherRepositoryInterface $publisherRepository,
    ) {}

    public function getList(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $filters = [
            'q' => $request->query('q', ''),
        ];

        $publishers = $this->publisherRepository->getPublishers($filters, $perPage);
        $publishers->appends($request->query());

        return $this->successResponse($publishers);
    }

    public function getDetail(int $id)
    {
        $publisher = $this->publisherRepository->getDetail($id);

        if (!$publisher) {
            return $this->errorResponse('Publisher not found.');
        }

        return $this->successResponse($publisher);
    }

    public function create(FormPublisherRequest $request)
    {
        $data = $request->validated();

        $publisher = $this->publisherRepository->create($data);
        return $this->successResponse($publisher, 'Publisher created successfully.', 201);
    }

    public function update(int $id, FormPublisherRequest $request)
    {
        $data = $request->validated();

        $publisher = $this->publisherRepository->update($id, $data);
        if (!$publisher) {
            return $this->errorResponse('Publisher not found.');
        }

        return $this->successResponse($publisher, 'Publisher updated successfully.');
    }

    public function delete(int $id)
    {
        $publisher = $this->publisherRepository->getDetail($id);

        if (!$publisher) {
            return $this->errorResponse('Publisher not found.');
        }

        if ($publisher->books->isNotEmpty()) {
            return $this->errorResponse('Cannot delete publisher with associated books.');
        }

        $deleted = $this->publisherRepository->delete($id);

        if (!$deleted) {
            return $this->errorResponse('Failed to delete publisher.');
        }

        return $this->successResponse('Publisher deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids) || !is_array($ids)) {
            return $this->errorResponse('Invalid publisher IDs.');
        }

        $deletedCount = $this->publisherRepository->bulkDelete($ids);

        return $this->successResponse("Deleted {$deletedCount} publishers successfully.");
    }
}
