<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\TransactionStatus;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class ViewMyTickets extends Component
{
    public string $identityDocument = '';
    public bool $searched = false;
    public $tickets = [];

    public function search(): void
    {
        $this->validate([
            'identityDocument' => ['required', 'regex:/^[0-9]+$/', 'min:6'],
        ], [
            'identityDocument.regex' => __('messages.validation_document_numeric'),
            'identityDocument.min' => __('messages.validation_document_min'),
        ]);

        $this->tickets = Transaction::with('raffle')
            ->where('identity_document', $this->identityDocument)
            ->where('status', TransactionStatus::Confirmed)
            ->latest()
            ->get();

        $this->searched = true;

        if ($this->tickets->isEmpty()) {
            $this->identityDocument = '';
        }
    }

    public function render(): View
    {
        return view('livewire.view-my-tickets')
            ->layout('layouts.public', ['title' => __('messages.view_my_tickets')]);
    }
}
