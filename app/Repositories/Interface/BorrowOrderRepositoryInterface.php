<?php

namespace App\Repositories\Interface;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Constant\PerPage;

interface BorrowOrderRepositoryInterface
{
    public function getBorrowOrders(array $filters, int $perPage = PerPage::DEFAULT): LengthAwarePaginator;

    public function getDetail(int $id): ?Order;

    public function getOrderWithDetails(int $orderId): ?Order;

    public function findOrderAndDetail(int $orderId, int $detailId): array;

    public function updateDetailStatus(OrderDetail $detail, int $status): OrderDetail;
}
