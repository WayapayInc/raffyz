<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Page;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class TermsPage extends Component
{
    public function render(): View
    {
        $page = Page::where('slug', 'terms-and-conditions')->first();

        return view('livewire.terms-page', [
            'page' => $page,
        ])->layout('layouts.public', ['title' => __('messages.terms_and_conditions')]);
    }
}
