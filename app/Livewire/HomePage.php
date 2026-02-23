<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Raffle;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class HomePage extends Component
{
    public function render(): View
    {
        $featuredRaffle = Raffle::active()
            ->latest()
            ->first();

        $raffles = Raffle::where('id', '!=', $featuredRaffle?->id)
            ->latest()
            ->take(6)
            ->get();

        return view('livewire.home-page', [
            'featuredRaffle' => $featuredRaffle,
            'raffles' => $raffles,
        ])->layout('layouts.public');
    }
}
