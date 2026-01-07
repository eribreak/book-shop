<?php

namespace App\Repositories\Repository;

use App\Models\Review;
use App\Repositories\Interface\ReviewRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Constant\PerPage;

class ReviewRepository implements ReviewRepositoryInterface
{
    public function getReviews(array $filters, int $perPage = PerPage::DEFAULT): LengthAwarePaginator
    {
        $reviewerName = isset($filters['reviewer_name']) ? trim((string) $filters['reviewer_name']) : '';
        $status = isset($filters['status']) ? (int) $filters['status'] : null;

        $query = Review::query()
            ->with(['user', 'book'])
            ->when(!empty($reviewerName), function ($q) use ($reviewerName) {
                $like = '%' . $reviewerName . '%';
                $q->whereHas('user', function ($qq) use ($like) {
                    $qq->where('name', 'like', $like);
                });
            })
            ->when($status !== null, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->latest();

        return $query->paginate($perPage);
    }

    public function updateStatus(int $id, int $status): bool
    {
        $review = Review::find($id);
        if ($review) {
            $review->update(['status' => $status]);
            return true;
        }
        return false;
    }
}
