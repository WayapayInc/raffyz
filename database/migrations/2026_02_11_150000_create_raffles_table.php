<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('raffles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->dateTime('end_date');
            $table->decimal('ticket_price', 10, 2);
            $table->unsignedInteger('total_tickets');
            $table->integer('minimum_purchase_ticket')->default(1);
            $table->unsignedInteger('tickets_sold')->default(0);
            $table->json('images');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raffles');
    }
};
