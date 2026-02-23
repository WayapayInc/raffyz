<?php

declare(strict_types=1);

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Default settings
        $settings = [
            'platform_name' => 'Raffyz',
            'logo_light' => 'assets/settings/logo-light.png',
            'logo_dark' => 'assets/settings/logo-dark.png',
            'favicon' => 'assets/settings/favicon.png',
            'primary_color' => '#08b83dc9',
            'contact_email' => 'support@raffyz.com',
            'currency_code' => 'USD',
            'currency_symbol' => '$',
            'currency_position' => 'left',
            'decimal_format' => 'dot',
            'site_language' => 'en',
            'default_theme' => 'dark',
            'max_purchase_tickets' => '20',
            'version' => '1.0',
            'license_key' => '',
            'license_type' => '',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        // Delete default settings
        Setting::whereIn('key', [
            'platform_name',
            'primary_color',
            'platform_font',
            'contact_email',
            'currency_code',
            'currency_symbol',
            'currency_position',
            'decimal_format',
            'site_language',
            'default_theme',
            'max_purchase_tickets',
            'version',
            'license_key',
            'license_type',
        ])->delete();
    }
};
