<?php

namespace App\Http\Controllers;

use App\Http\Requests\BorrowOrder\BorrowOrderListRequest;
use App\Http\Requests\BorrowOrder\UpdateBorrowOrderDetailStatusRequest;
use App\Services\BorrowOrderService;
use Exception;
use Illuminate\Http\Response;

class BorrowOrderController extends Controller
{
    public function __construct(
        private readonly BorrowOrderService $borrowOrderService,
    ) {}

    public function getList(BorrowOrderListRequest $request)
    {
        $perPage = (int) $request->query('per_page');
        $filters = [
            'q' => $request->query('q', ''),
            'overdue' => $request->boolean('overdue', false),
        ];

        $orders = $this->borrowOrderService->getList($filters, $perPage);
        $orders->appends($request->query());

        return $this->successResponse($orders);
    }

    public function getDetail(int $id)
    {
        $data = $this->borrowOrderService->getDetail($id);

        return $this->successResponse($data);
    }

    public function updateDetailStatus(UpdateBorrowOrderDetailStatusRequest $request, int $orderId, int $detailId)
    {
        try {
            $this->borrowOrderService->updateDetailStatus(
                $orderId,
                $detailId,
                (int) $request->validated('status'),
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_CONFLICT);
        }

        return $this->successResponse('Updated successfully.');
    }
}
