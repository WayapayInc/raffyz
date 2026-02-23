<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\TransactionStatus;
use App\Models\PaymentMethod;
use App\Models\Raffle;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentPendingMail;
use App\Models\User;
use App\Models\Setting;

final class RaffleDetailPage extends Component
{
    public Raffle $raffle;

    public $quantity = 1;
    public int $step = 1; // 1: quantity, 2: payment method, 3: user info
    public bool $showModal = false;
    public bool $showSuccess = false;

    public ?int $selectedPaymentMethodId = null;

    #[Validate(['required', 'string', 'min:2', 'max:255', 'regex:/^([^0-9]*)$/'])]
    public string $fullName = '';

    #[Validate(['required', 'string', 'min:6', 'max:50'])]
    public string $identityDocument = '';

    #[Validate(['required', 'email', 'max:255'])]
    public string $email = '';

    #[Validate(['required', 'string', 'min:10', 'max:30', 'regex:/^[0-9\s\-\+\(\)]+$/'])]

    public string $phone = '';

    #[Validate(['required', 'string', 'min:4', 'max:255'])]
    public string $referenceNumber = '';

    public function mount(string $slug): void
    {
        $this->raffle = Raffle::where('slug', $slug)->firstOrFail();

        $min = $this->raffle->minimum_purchase_ticket ?? 1;
        if ($min > 1) {
            $this->quantity = $min;
        }
    }

    public function incrementQuantity(): void
    {
        $max = (int) Setting::get('max_purchase_tickets', 100);
        if ($this->quantity < $max && $this->quantity < $this->raffle->tickets_available) {
            $this->quantity++;
        }
    }

    public function decrementQuantity(): void
    {
        $min = $this->raffle->minimum_purchase_ticket ?? 1;
        if ($this->quantity > $min) {
            $this->quantity--;
        }
    }

    public function updatedQuantity(): void
    {
        $this->quantity = (int) $this->quantity;

        $min = $this->raffle->minimum_purchase_ticket ?? 1;
        $max = (int) Setting::get('max_purchase_tickets', 100);

        if ($this->quantity < $min) {
            $this->quantity = $min;
            $this->dispatch('quantity-invalid', message: __('messages.min_tickets', ['count' => $min]));
        }

        if ($this->quantity > $max) {
            $this->quantity = $max;
            $this->dispatch('quantity-invalid', message: __('messages.max_tickets', ['count' => $max]));
        }

        if ($this->quantity > $this->raffle->tickets_available) {
            $this->quantity = $this->raffle->tickets_available;
        }
    }

    public function openModal(): void
    {
        if (! $this->raffle->isActive()) {
            return;
        }

        $min = $this->raffle->minimum_purchase_ticket ?? 1;

        if ($this->quantity < $min) {
            $this->dispatch('quantity-invalid', message: __('messages.min_tickets', ['count' => $min]));
            return;
        }

        $this->step = 1;
        $this->showModal = true;
        $this->showSuccess = false;
        $this->js("document.body.style.overflow = 'hidden'");
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->js("document.body.style.overflow = ''");
        $this->reset(['step', 'selectedPaymentMethodId', 'fullName', 'identityDocument', 'email', 'phone', 'referenceNumber']);
        $this->resetValidation();
    }

    public function nextStep(): void
    {
        if ($this->step === 1) {
            $min = $this->raffle->minimum_purchase_ticket ?? 1;
            if ($this->quantity < $min || $this->quantity > 100 || $this->quantity > $this->raffle->tickets_remaining) {
                return;
            }
            $this->step = 2;
            $this->dispatch('scroll-top-modal');
        } elseif ($this->step === 2) {
            if (! $this->selectedPaymentMethodId) {
                return;
            }
            $this->step = 3;
            $this->dispatch('scroll-top-modal');
        }
    }

    public function selectPaymentMethod(int $id): void
    {
        $this->selectedPaymentMethodId = $id;
    }

    public function previousStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function submitPurchase(): void
    {
        $this->validate();

        if (! $this->raffle->isActive()) {
            return;
        }

        $paymentMethod = PaymentMethod::findOrFail($this->selectedPaymentMethodId);

        try {
            DB::transaction(function () use ($paymentMethod) {
                /** @var Raffle $raffle */
                $raffle = Raffle::lockForUpdate()->find($this->raffle->id);

                $ticketNumbers = $this->generateUniqueTicketNumbers($raffle, $this->quantity);

                $transaction = Transaction::create([
                    'raffle_id' => $raffle->id,
                    'payment_method_id' => $paymentMethod->id,
                    'full_name' => $this->fullName,
                    'identity_document' => $this->identityDocument,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'reference_number' => $this->referenceNumber,
                    'tickets_quantity' => $this->quantity,
                    'total_amount' => $this->quantity * $raffle->ticket_price,
                    'rate_applied' => $paymentMethod->exchange_rate,
                    'amount_charged' => ($this->quantity * $raffle->ticket_price) * $paymentMethod->exchange_rate, // Fixed calculation logic if needed
                    'tickets_bought' => $ticketNumbers,
                    'status' => TransactionStatus::Pending,
                ]);

                // Notify Admins
                $adminEmails = User::where('role', 'admin')->pluck('email');
                foreach ($adminEmails as $email) {
                    Mail::to($email)->send(new PaymentPendingMail($transaction));
                }
            });

            $this->showSuccess = true;
            $this->dispatch('payment-submitted');
            $this->raffle->refresh();
        } catch (\Exception $e) {
            $this->addError('quantity', $e->getMessage());
        }
    }

    /**
     * @return list<int>
     */
    private function generateUniqueTicketNumbers(Raffle $raffle, int $quantity): array
    {
        $existingTickets = $raffle->transactions()
            ->whereIn('status', [TransactionStatus::Confirmed, TransactionStatus::Pending])
            ->whereNotNull('tickets_bought')
            ->pluck('tickets_bought')
            ->flatten()
            ->toArray();

        $maxNumber = $raffle->total_tickets;
        $availableNumbers = array_diff(range(1, $maxNumber), $existingTickets);

        if (count($availableNumbers) < $quantity) {
            throw new \RuntimeException(__('messages.tickets_not_available'));
        }

        $selectedKeys = array_rand($availableNumbers, $quantity);

        if (! is_array($selectedKeys)) {
            $selectedKeys = [$selectedKeys];
        }

        $tickets = array_map(
            fn($key) => $availableNumbers[$key],
            $selectedKeys,
        );

        sort($tickets);

        return array_values($tickets);
    }

    public function getTotalProperty(): string
    {
        return number_format($this->quantity * (float) $this->raffle->ticket_price, 2);
    }

    public function getSelectedPaymentMethodProperty(): ?PaymentMethod
    {
        if (! $this->selectedPaymentMethodId) {
            return null;
        }
        return PaymentMethod::find($this->selectedPaymentMethodId);
    }

    public function getConvertedTotalProperty(): ?string
    {
        $method = $this->selectedPaymentMethod;
        if (! $method || $method->exchange_rate == 1) {
            return null;
        }

        $total = $this->quantity * (float) $this->raffle->ticket_price;
        $converted = $total * $method->exchange_rate;

        return number_format($converted, 2) . ' ' . $method->currency_code;
    }

    public function getRawTotal(): float
    {
        return $this->quantity * (float) $this->raffle->ticket_price;
    }

    public function render(): View
    {
        $description = trim(preg_replace('/\s+/', ' ', strip_tags($this->raffle->description)));

        return view('livewire.raffle-detail-page', [
            'paymentMethods' => PaymentMethod::active()->get(),
        ])->layout('layouts.public', [
            'title' => $this->raffle->title,
            'metaDescription' => \Illuminate\Support\Str::words($description, 30),
        ]);
    }
}
