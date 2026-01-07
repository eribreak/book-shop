<?php

namespace App\Http\Controllers;

use App\Repositories\Interface\ReviewRepositoryInterface;
use Illuminate\Http\Request;
use App\Constant\PerPage;
use App\Http\Requests\Review\UpdateStatusRequest;

class ReviewController extends Controller
{
    public function __construct(
        private readonly ReviewRepositoryInterface $reviewRepository,
    ) {}

    public function getList(Request $request)
    {
        $perPage = (int) $request->query('per_page', PerPage::DEFAULT);
        $filters = [
            'reviewer_name' => $request->query('reviewer_name', ''),
            'status' => $request->query('status'),
        ];

        $reviews = $this->reviewRepository->getReviews($filters, $perPage);
        $reviews->appends($request->query());

        return $this->successResponse($reviews);
    }

    public function updateStatus(int $id, UpdateStatusRequest $request)
    {
        $data = $request->validated();

        $review = $this->reviewRepository->updateStatus($id, $data['status']);

        if (!$review) {
            return $this->errorResponse('Review not found.');
        }

        return $this->successResponse('Review status updated successfully.');
    }
}
