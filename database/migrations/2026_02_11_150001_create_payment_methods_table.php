<?php

declare(strict_types=1);

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('currency_code')->default('USD');
            $table->text('instructions');
            $table->string('logo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('exchange_rate', 10, 2)->default(1.00);
            $table->timestamps();
        });

        // Payment methods
        PaymentMethod::updateOrCreate(
            ['name' => 'Zelle'],
            [
                'instructions' => '<p>Send payment to: <strong>[EMAIL_ADDRESS]</strong></p><p>Include your full name in the memo.</p>',
                'currency_code' => 'USD',
                'logo' => 'assets/payment-methods/zelle.png',
                'is_active' => false,
                'exchange_rate' => 1.00,
            ],
        );

        PaymentMethod::updateOrCreate(
            ['name' => 'PayPal'],
            [
                'instructions' => '<p>Send payment to: <strong>[EMAIL_ADDRESS]</strong></p><p>Send as Friends & Family.</p>',
                'currency_code' => 'USD',
                'logo' => 'assets/payment-methods/paypal.png',
                'is_active' => false,
                'exchange_rate' => 1.35,
            ],
        );

        PaymentMethod::updateOrCreate(
            ['name' => 'Nequi'],
            [
                'instructions' => '<p>Phone: <strong>[PHONE_NUMBER]</strong></p><p>Name: <strong>Example Name</strong></p>',
                'currency_code' => 'COP',
                'logo' => 'assets/payment-methods/nequi.jpg',
                'is_active' => false,
                'exchange_rate' => 4000.00,
            ],
        );

        PaymentMethod::updateOrCreate(
            ['name' => 'Wise'],
            [
                'instructions' => '<p>Send payment to: <strong>[EMAIL_ADDRESS]</strong></p><p>Send as Friends & Family.</p>',
                'currency_code' => 'USD',
                'logo' => 'assets/payment-methods/wise.png',
                'is_active' => false,
                'exchange_rate' => 1.00,
            ],
        );

        PaymentMethod::updateOrCreate(
            ['name' => 'PIX'],
            [
                'instructions' => '<p>[Chave PIX]</p>',
                'currency_code' => 'BRL',
                'logo' => 'assets/payment-methods/pix.png',
                'is_active' => false,
                'exchange_rate' => 5.22,
            ],
        );

        PaymentMethod::updateOrCreate(
            ['name' => 'Revolut'],
            [
                'instructions' => '<p>[EMAIL_ADDRESS]</p>',
                'currency_code' => 'EUR',
                'logo' => 'assets/payment-methods/revolut.png',
                'is_active' => false,
                'exchange_rate' => 0.93,
            ],
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
