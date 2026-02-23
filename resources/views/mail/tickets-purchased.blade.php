<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 10px 10px 0 0; text-align: center; }
        .content { background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; }
        .tickets { background: white; border: 2px dashed #667eea; border-radius: 8px; padding: 20px; margin: 20px 0; text-align: center; }
        .ticket-number { display: inline-block; background: #667eea; color: white; padding: 8px 16px; border-radius: 20px; margin: 4px; font-weight: bold; font-size: 14px; }
        .footer { background: #1f2937; color: #9ca3af; padding: 20px; border-radius: 0 0 10px 10px; text-align: center; font-size: 12px; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb; }
        .label { font-weight: bold; color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">🎟️ {{ __('messages.tickets_purchased_title') }}</h1>
    </div>

    <div class="content">
        <p>{{ __('messages.hello') }}, <strong>{{ $transaction->full_name }}</strong>!</p>
        <p>{{ __('messages.tickets_purchased_message') }}</p>

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

        <div class="tickets">
            <h3 style="margin-top: 0;">{{ __('messages.your_ticket_numbers') }}</h3>
            @foreach ($transaction->tickets_bought as $ticket)
                <span class="ticket-number">#{{ $ticket }}</span>
            @endforeach
        </div>

        <p style="color: #6b7280; font-size: 13px;">{{ __('messages.keep_document_id') }}</p>
    </div>

    <div class="footer">
        <p>{{ \App\Models\Setting::get('platform_name', 'Raffyz') }}</p>
    </div>
</body>
</html>
