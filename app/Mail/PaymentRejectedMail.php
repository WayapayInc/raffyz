<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class PaymentRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Transaction $transaction,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('messages.payment_rejected_subject', [
                'raffle' => $this->transaction->raffle->title,
            ]),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.payment-rejected',
        );
    }
}
