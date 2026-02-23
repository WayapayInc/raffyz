<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\TransactionStatus;
use App\Models\Transaction;
use Illuminate\Support\Facades\Mail;

final class RejectTransactionAction
{
    public function execute(Transaction $transaction): void
    {
        if (! $transaction->isPending()) {
            throw new \RuntimeException('Transaction is not pending.');
        }

        $transaction->update([
            'status' => TransactionStatus::Rejected,
            'tickets_bought' => null,
        ]);

        Mail::to($transaction->email)->send(
            new \App\Mail\PaymentRejectedMail($transaction),
        );
    }
}
