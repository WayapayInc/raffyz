<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%); color: white; padding: 30px; border-radius: 10px 10px 0 0; text-align: center; }
        .content { background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; }
        .footer { background: #1f2937; color: #9ca3af; padding: 20px; border-radius: 0 0 10px 10px; text-align: center; font-size: 12px; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb; }
        .label { font-weight: bold; color: #6b7280; }
        .btn { display: inline-block; background-color: #3b82f6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 20px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">⚠️ {{ __('messages.payment_pending_title') }}</h1>
    </div>

    <div class="content">
        <p>{{ __('messages.payment_pending_message', ['name' => $transaction->full_name]) }}</p>

        <div style="margin: 15px 0;">
            <div class="info-row">
                <span class="label">{{ __('messages.raffle') }}:</span>
                <span>{{ $transaction->raffle->title }}</span>
            </div>
            <div class="info-row">
                <span class="label">{{ __('messages.quantity') }}:</span>
                <span>{{ $transaction->tickets_quantity }}</span>
            </div>
            <div class="info-row">
                <span class="label">{{ __('messages.total_amount') }}:</span>
                <span>{{ \App\Helpers\CurrencyHelper::format($transaction->total_amount) }}</span>
            </div>
            <div class="info-row">
                <span class="label">{{ __('messages.status') }}:</span>
                <span style="color: #d97706; font-weight: bold;">{{ $transaction->status->getLabel() }}</span>
            </div>
        </div>

        <div style="text-align: center;">
            <a href="{{ \Filament\Pages\Dashboard::getUrl() }}" class="btn">{{ __('messages.approve_link_text') }}</a>
        </div>
    </div>

    <div class="footer">
        <p>{{ \App\Models\Setting::get('platform_name', 'Raffyz') }}</p>
    </div>
</body>
</html>
