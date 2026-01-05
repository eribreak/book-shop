<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Status;
use App\Repositories\Interface\BorrowOrderRepositoryInterface;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class BorrowOrderService
{
    public function __construct(
        private readonly BorrowOrderRepositoryInterface $borrowOrderRepository,
    ) {}

    public function getList(array $filters, int $perPage): LengthAwarePaginator
    {
        $orders = $this->borrowOrderRepository->getBorrowOrders($filters, $perPage);

        $orders->getCollection()->transform(function (Order $order) {
            return $this->mapOrderSummary($order);
        });

        return $orders;
    }

    public function getDetail(int $id): ?array
    {
        $order = $this->borrowOrderRepository->getDetail($id);

        $details = $order->orderDetails->map(function (OrderDetail $detail) use ($order) {
            return [
                'id' => $detail->id,
                'book_id' => $detail->book_id,
                'book_name' => $detail->book_name,
                'book_image_url' => optional($detail->book)->image_url,
                'quantity' => (int) $detail->quantity,
                'borrowed_at' => optional($order->created_at)->toDateTimeString(),
                'due_date' => optional($detail->due_date)->toDateTimeString(),
                'status' => (int) $detail->status,
            ];
        });

        return [
            'order' => $this->mapOrderSummary($order),
            'details' => $details,
        ];
    }

    public function updateDetailStatus(int $orderId, int $detailId, int $status): void
    {
        ['order' => $orderBeforeUpdate, 'detail' => $detailBeforeUpdate] =
            $this->borrowOrderRepository->findOrderAndDetail($orderId, $detailId);

        if ((int) $detailBeforeUpdate->status === Status::RETURNED->value) {
            throw new Exception('Can not update detail with status returned');
        }

        $this->borrowOrderRepository->updateDetailStatus($detailBeforeUpdate, $status);

        $order = $orderBeforeUpdate->load('orderDetails');

        $computedStatus = $this->computeOrderStatus($order);
        if ((int) $order->status !== $computedStatus) {
            $order->update(['status' => $computedStatus]);
            $order->refresh();
        }
    }

    public function computeOrderStatus(Order $order): int
    {
        $details = $order->orderDetails;

        if ($details->isEmpty()) {
            return (int) $order->status;
        }

        if ($details->contains(fn(OrderDetail $detail) => (int) $detail->status === Status::OVERDUE->value)) {
            return Status::OVERDUE->value;
        }

        if ($details->contains(fn(OrderDetail $detail) => (int) $detail->status === Status::BORROWING->value)) {
            return Status::BORROWING->value;
        }

        if ($details->every(fn(OrderDetail $detail) => (int) $detail->status === Status::LOST->value)) {
            return Status::LOST->value;
        }

        return Status::RETURNED->value;
    }

    private function mapOrderSummary(Order $order): array
    {
        return [
            'id' => $order->id,
            'full_name' => $order->full_name,
            'email' => $order->email,
            'borrowed_at' => $order->created_at->toDateTimeString(),
            'status' => (int) $order->status,
        ];
    }
}
