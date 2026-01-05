<?php

namespace App\Repositories\Repository;

use App\Models\Order;
use App\Models\Status;
use App\Repositories\Interface\BorrowOrderRepositoryInterface;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class BorrowOrderRepository implements BorrowOrderRepositoryInterface
{
    public function getBorrowOrders(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $name = isset($filters['q']) ? trim((string) $filters['q']) : '';
        $overdue = (bool) ($filters['overdue'] ?? false);

        $query = Order::query()
            ->select(['id', 'user_id', 'full_name', 'email', 'status', 'created_at'])
            ->with(['orderDetails:id,order_id,status,due_date'])
            ->when(!empty($name), function ($query) use ($name) {
                $like = "%{$name}%";
                $query->where(function (Builder $sub) use ($like) {
                    $sub->where('full_name', 'like', $like)
                        ->orWhere('email', 'like', $like);
                });
            })
            ->when($overdue === false, function ($q) {
                $q->whereNot('status', Status::OVERDUE->value);
            })
            ->orderByDesc('created_at');

        return $query->paginate($perPage);
    }

    public function getDetail(int $id): ?Order
    {
        return Order::query()
            ->with([
                'orderDetails' => function ($q) {
                    $q
                        ->select(['id', 'order_id', 'book_id', 'book_name', 'quantity', 'status', 'due_date', 'created_at'])
                        ->with('book:id,image_url');
                },
            ])
            ->find($id);
    }

    public function getOrderWithDetails(int $orderId): ?Order
    {
        return Order::with('orderDetails')->find($orderId);
    }

    public function findOrderAndDetail(int $orderId, int $detailId): array
    {
        $order = Order::with('orderDetails')->find($orderId);
        $detail = $order->orderDetails->firstWhere('id', $detailId);

        return [
            'order' => $order,
            'detail' => $detail,
        ];
    }

    public function updateDetailStatus(\App\Models\OrderDetail $detail, int $status): \App\Models\OrderDetail
    {
        $detail->update(['status' => $status]);
        $detail->refresh();

        return $detail;
    }
}
