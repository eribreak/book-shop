<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OverdueReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<int, string>  $bookTitles
     */
    public function __construct(
        public int $orderId,
        public array $bookTitles,
        public ?string $recipientName = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Overdue Borrow Reminder',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.overdue-reminder',
            with: [
                'orderId' => $this->orderId,
                'bookTitles' => $this->bookTitles,
                'recipientName' => $this->recipientName,
            ],
        );
    }
}
