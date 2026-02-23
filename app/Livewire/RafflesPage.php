<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Raffle;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class RafflesPage extends Component
{
    public string $filter = 'all';

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
    }

    public function render(): View
    {
        $query = Raffle::latest();

        if ($this->filter === 'active') {
            $query->active();
        } elseif ($this->filter === 'finished') {
            $query->finished();
        }

        return view('livewire.raffles-page', [
            'raffles' => $query->get(),
        ])->layout('layouts.public', ['title' => __('messages.raffles')]);
    }
}
