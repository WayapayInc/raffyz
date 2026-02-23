<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); color: white; padding: 30px; border-radius: 10px 10px 0 0; text-align: center; }
        .content { background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; }
        .footer { background: #1f2937; color: #9ca3af; padding: 20px; border-radius: 0 0 10px 10px; text-align: center; font-size: 12px; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb; }
        .label { font-weight: bold; color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">❌ {{ __('messages.payment_rejected_title') }}</h1>
    </div>

    <div class="content">
        <p>{{ __('messages.hello') }}, <strong>{{ $transaction->full_name }}</strong>!</p>
        <p>{{ __('messages.payment_rejected_message', ['raffle' => $transaction->raffle->title]) }}
            {{ \App\Models\Setting::get('contact_email') }}
        </p>

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
        </div>

        <p style="color: #6b7280; font-size: 13px;">{{ __('messages.all_rights_reserved') }}</p>
    </div>

    <div class="footer">
        <p>{{ \App\Models\Setting::get('platform_name', 'Raffyz') }}</p>
    </div>
</body>
</html>
