@component('mail::message')
# New Payment Received

Dear {{ $property->agent->user->name }},

A new payment has been received for one of your properties.

**Payment Details:**
- Property: {{ $property->title }}
- Amount: ${{ number_format($payment->amount, 2) }}
- Payment Method: {{ ucfirst($payment->payment_method) }}
- Transaction ID: {{ $payment->transaction_id }}
- Date: {{ $payment->created_at->format('F j, Y') }}
- Customer: {{ $user->name }}

@component('mail::button', ['url' => route('payment.show', $payment->id)])
View Payment Details
@endcomponent

This payment has been automatically recorded in your account.

Thanks,<br>
{{ config('app.name') }}
@endcomponent 