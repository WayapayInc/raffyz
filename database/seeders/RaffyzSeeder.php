<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PaymentMethod;
use App\Models\Raffle;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class RaffyzSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@raffyz.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
        );

        // Default settings
        $settings = [
            'platform_name' => 'Raffyz',
            'primary_color' => '#7c3aed',
            'platform_font' => 'Inter',
            'contact_email' => 'support@raffyz.com',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }

        // Payment methods
        PaymentMethod::updateOrCreate(
            ['name' => 'Zelle'],
            [
                'instructions' => '<p>Send payment to: <strong>payments@raffyz.com</strong></p><p>Include your full name in the memo.</p>',
                'is_active' => true,
                'exchange_rate' => 1.0000,
            ],
        );

        PaymentMethod::updateOrCreate(
            ['name' => 'PayPal'],
            [
                'instructions' => '<p>Send payment to: <strong>payments@raffyz.com</strong></p><p>Send as Friends & Family.</p>',
                'is_active' => true,
                'exchange_rate' => 1.0000,
            ],
        );

        PaymentMethod::updateOrCreate(
            ['name' => 'Bank Transfer'],
            [
                'instructions' => '<p>Bank: <strong>Example Bank</strong></p><p>Account: <strong>1234567890</strong></p><p>Routing: <strong>021000021</strong></p>',
                'is_active' => true,
                'exchange_rate' => 1.0000,
            ],
        );

        // Sample raffle
        Raffle::updateOrCreate(
            ['slug' => 'win-playstation-5-slim'],
            [
                'title' => 'Win a PlayStation 5 Slim',
                'description' => '<h3>Grand Prize!</h3><p>Enter for a chance to win a brand new PlayStation 5 Slim console. This amazing prize includes:</p><ul><li>PlayStation 5 Slim Console</li><li>DualSense Wireless Controller</li><li>HDMI Cable</li><li>USB-C Cable</li></ul><p>Don\'t miss out on this incredible opportunity!</p>',
                'end_date' => now()->addMonths(1),
                'ticket_price' => 5.00,
                'total_tickets' => 1000,
                'tickets_sold' => 0,
                'images' => [],
                'status' => 'active',
            ],
        );

        Raffle::updateOrCreate(
            ['slug' => 'win-iphone-16-pro'],
            [
                'title' => 'Win an iPhone 16 Pro',
                'description' => '<h3>Tech Raffle!</h3><p>Win a brand new iPhone 16 Pro with 256GB storage. Features include:</p><ul><li>A18 Pro chip</li><li>48MP Camera System</li><li>Titanium Design</li></ul>',
                'end_date' => now()->addMonths(2),
                'ticket_price' => 10.00,
                'total_tickets' => 500,
                'tickets_sold' => 0,
                'images' => [],
                'status' => 'active',
            ],
        );

        // Terms & Conditions page
        Page::updateOrCreate(
            ['slug' => 'terms-and-conditions'],
            [
                'title' => 'Terms and Conditions',
                'content' => '<h2>1. Introduction</h2><p>Welcome to Raffyz. By purchasing raffle tickets, you agree to these terms and conditions.</p><h2>2. Eligibility</h2><p>You must be 18 years or older to participate in any raffle.</p><h2>3. Ticket Purchase</h2><p>All ticket sales are final. No refunds will be issued once a ticket has been purchased.</p><h2>4. Winner Selection</h2><p>Winners are selected at random using a certified random number generator. Results are final and binding.</p><h2>5. Prize Delivery</h2><p>Winners will be contacted via the email provided during ticket purchase. Prizes will be shipped within 30 days of the draw.</p><h2>6. Contact</h2><p>For any questions, please contact us at support@raffyz.com</p>',
            ],
        );
    }
}
