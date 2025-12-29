<?php

namespace App\Repositories\Repository;

use App\Repositories\Interface\BookReportRepositoryInterface;
use Illuminate\Support\Facades\DB;

class BookReportRepository implements BookReportRepositoryInterface
{
    public function getBorrowedQuantityByMonth(int $year): array
    {
        $rows = DB::table('orders')
            ->selectRaw('MONTH(orders.created_at) as month')
            ->selectRaw('SUM(order_details.quantity) as total_borrowed')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->whereYear('orders.created_at', $year)
            ->whereNull('orders.deleted_at')
            ->whereNull('order_details.deleted_at')
            ->groupByRaw('MONTH(orders.created_at)')
            ->orderByRaw('MONTH(orders.created_at)')
            ->get();

        $byMonth = [];
        foreach ($rows as $row) {
            $month = (int) ($row->month ?? 0);
            if ($month >= 1 && $month <= 12) {
                $byMonth[$month] = (int) ($row->total_borrowed ?? 0);
            }
        }

        $result = [];
        for ($m = 1; $m <= 12; $m++) {
            $result[] = [
                'month' => $m,
                'total_borrowed' => $byMonth[$m] ?? 0,
            ];
        }

        return $result;
    }

    public function getTopBorrowers(int $limit = 10): array
    {
        $limit = max(1, min((int) $limit, 100));

        $rows = DB::table('orders')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->whereNull('orders.deleted_at')
            ->whereNull('users.deleted_at')
            ->whereNull('order_details.deleted_at')
            ->groupBy('users.id')
            ->select(
                'users.id as user_id',
                'users.full_name',
            )
            ->selectRaw('SUM(order_details.quantity) as total_borrowed')
            ->orderByDesc('total_borrowed')
            ->limit($limit)
            ->get();

        return $rows->map(fn($r) => [
            'user_id' => (int) $r->user_id,
            'full_name' => (string) $r->full_name,
            'total_borrowed' => (int) $r->total_borrowed,
        ])->all();
    }

    public function getTopBorrowedBooks(int $limit = 10): array
    {
        $limit = max(1, min((int) $limit, 100));

        $rows = DB::table('order_details')
            ->join('books', 'books.id', '=', 'order_details.book_id')
            ->whereNull('order_details.deleted_at')
            ->whereNull('books.deleted_at')
            ->groupBy('books.id')
            ->select(
                'books.id as book_id',
                'books.name',
                'books.image_url'
            )
            ->selectRaw('SUM(order_details.quantity) as total_borrowed')
            ->orderByDesc('total_borrowed')
            ->limit($limit)
            ->get();

        return $rows->map(fn($r) => [
            'book_id' => (int) $r->book_id,
            'name' => (string) $r->name,
            'image_url' => $r->image_url !== null ? (string) $r->image_url : null,
            'total_borrowed' => (int) $r->total_borrowed,
        ])->all();
    }
}
