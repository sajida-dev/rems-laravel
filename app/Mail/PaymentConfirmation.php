<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function build()
    {
        return $this->subject('Payment Confirmation - ' . config('app.name'))
            ->markdown('emails.payments.confirmation')
            ->with([
                'payment' => $this->payment,
                'property' => $this->payment->transaction->property,
                'user' => $this->payment->transaction->user,
            ]);
    }
}
