<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raffle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_method_id')->constrained()->cascadeOnDelete();
            $table->string('full_name');
            $table->string('identity_document');
            $table->string('email');
            $table->string('phone');
            $table->string('reference_number');
            $table->unsignedInteger('tickets_quantity');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('rate_applied', 10, 2)->nullable();
            $table->decimal('amount_charged', 10, 2)->nullable();
            $table->json('tickets_bought')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
