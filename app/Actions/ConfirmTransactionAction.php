<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\TransactionStatus;
use App\Mail\TicketsPurchasedMail;
use App\Models\Raffle;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

final class ConfirmTransactionAction
{
    public function execute(Transaction $transaction): void
    {
        if (! $transaction->isPending()) {
            throw new \RuntimeException('Transaction is not pending.');
        }

        DB::transaction(function () use ($transaction): void {
            /** @var Raffle $raffle */
            $raffle = Raffle::lockForUpdate()->findOrFail($transaction->raffle_id);

            // Since tickets are already assigned in Pending status, just confirm.
            
            $transaction->update([
                'status' => TransactionStatus::Confirmed,
            ]);

            $raffle->increment('tickets_sold', $transaction->tickets_quantity);
        });

        $transaction->refresh();

        Mail::to($transaction->email)->send(
            new TicketsPurchasedMail($transaction),
        );
    }
}
