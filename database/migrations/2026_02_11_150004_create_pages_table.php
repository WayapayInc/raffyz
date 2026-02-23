<?php

declare(strict_types=1);

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->longText('content');
            $table->timestamps();
        });

        // Terms & Conditions page
        Page::updateOrCreate(
            ['slug' => 'terms-and-conditions'],
            [
                'title' => 'Terms and Conditions',
                'content' => '<h2>1. Introduction</h2><p>Welcome to Raffyz. By purchasing raffle tickets, you agree to these terms and conditions.</p><h2>2. Eligibility</h2><p>You must be 18 years or older to participate in any raffle.</p><h2>3. Ticket Purchase</h2><p>All ticket sales are final. No refunds will be issued once a ticket has been purchased.</p><h2>4. Winner Selection</h2><p>Winners are selected at random using a certified random number generator. Results are final and binding.</p><h2>5. Prize Delivery</h2><p>Winners will be contacted via the email provided during ticket purchase. Prizes will be shipped within 30 days of the draw.</p><h2>6. Contact</h2><p>For any questions, please contact us at support@raffyz.com</p>',
            ],
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');

        Page::where('slug', 'terms-and-conditions')->delete();
    }
};
