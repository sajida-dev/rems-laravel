@component('mail::message')
# Payment Confirmation

Dear {{ $user->name }},

Thank you for your payment. Your transaction has been completed successfully.

**Payment Details:**
- Property: {{ $property->title }}
- Amount: ${{ number_format($payment->amount, 2) }}
- Payment Method: {{ ucfirst($payment->payment_method) }}
- Transaction ID: {{ $payment->transaction_id }}
- Date: {{ $payment->created_at->format('F j, Y') }}

@component('mail::button', ['url' => route('payment.show', $payment->id)])
View Payment Details
@endcomponent

If you have any questions, please don't hesitate to contact us.

Thanks,<br>
{{ config('app.name') }}
@endcomponent 