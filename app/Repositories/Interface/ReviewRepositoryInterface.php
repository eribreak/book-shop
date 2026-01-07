<?php

namespace App\Repositories\Interface;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Review;
use App\Constant\PerPage;

interface ReviewRepositoryInterface
{
    public function getReviews(array $filters, int $perPage = PerPage::DEFAULT): LengthAwarePaginator;

    public function updateStatus(int $id, int $status): bool;
}
