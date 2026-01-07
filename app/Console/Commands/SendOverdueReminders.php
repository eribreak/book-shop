<?php

namespace App\Console\Commands;

use App\Mail\OverdueReminderMail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Status;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOverdueReminders extends Command
{
    protected $signature = 'borrow:send-overdue-reminders';

    protected $description = 'Send overdue borrow reminders to users who have overdue order details.';

    private const OVERDUE_STATUS = 2;

    public function handle(): int
    {
        $sent = 0;
        $skippedNoEmail = 0;
        $failed = 0;

        $query = OrderDetail::query()
            ->where('status', self::OVERDUE_STATUS)
            ->select('order_id')
            ->distinct()
            ->orderBy('order_id');

        $query->chunk(200, function ($rows) use (&$sent, &$skippedNoEmail, &$failed) {
            $orderIds = $rows->pluck('order_id')->filter()->values();
            if ($orderIds->isEmpty()) {
                return;
            }

            $orders = Order::query()
                ->whereIn('id', $orderIds)
                ->with([
                    'user:id,email,full_name',
                    'orderDetails' => function ($q) {
                        $q->where('status', self::OVERDUE_STATUS)
                            ->select('id', 'order_id', 'book_name', 'status');
                    },
                ])
                ->get();

            foreach ($orders as $order) {
                $email = $order->user?->email ?: $order->email;
                if (!$email) {
                    $skippedNoEmail++;
                    Log::warning('Overdue reminder skipped: missing email', ['order_id' => $order->id]);
                    continue;
                }

                $bookTitles = $order->orderDetails
                    ->pluck('book_name')
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

                if (count($bookTitles) === 0) {
                    continue;
                }

                try {
                    Mail::to($email)->send(new OverdueReminderMail(
                        orderId: (int) $order->id,
                        bookTitles: $bookTitles,
                        recipientName: $order->user?->full_name ?: $order->full_name,
                    ));

                    $sent++;
                } catch (\Throwable $e) {
                    $failed++;
                    Log::error('Failed to send overdue reminder', [
                        'order_id' => $order->id,
                        'email' => $email,
                        'exception' => $e,
                    ]);
                }
            }
        });

        $this->info("Overdue reminders done. sent={$sent}, skipped_no_email={$skippedNoEmail}, failed={$failed}");

        return self::SUCCESS;
    }
}
