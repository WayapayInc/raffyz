<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

final class PaymentPendingMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Transaction $transaction,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('messages.payment_pending_subject', [
                'raffle' => $this->transaction->raffle->title,
            ]),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.payment-pending',
        );
    }
}
