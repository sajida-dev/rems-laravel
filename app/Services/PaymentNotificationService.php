<?php

namespace App\Services;

use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\Redis;
use App\Notifications\RealTimeNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentConfirmation;
use App\Mail\PaymentReceived;
use Illuminate\Support\Facades\Log;

class PaymentNotificationService
{
    public function notifyPaymentRequired(Payment $payment)
    {
        $user = $payment->user;

        // Store payment status in Redis for quick access
        Redis::setex(
            "payment:{$payment->id}:status",
            3600, // 1 hour
            json_encode([
                'status' => 'pending',
                'amount' => $payment->amount,
                'property' => $payment->property->title
            ])
        );

        // Send real-time notification
        $user->notify(new RealTimeNotification(
            title: 'Payment Required',
            message: "Please complete your payment for {$payment->property->title}",
            type: 'payment',
            link: route('payment.create'),
            data: [
                'payment_id' => $payment->id,
                'property_id' => $payment->property_id,
                'amount' => $payment->amount
            ]
        ));
    }

    public function notifyPaymentReceived(Payment $payment)
    {
        try {
            // Send email to customer
            Mail::to($payment->transaction->user->email)
                ->send(new PaymentConfirmation($payment));

            // Send email to agent
            Mail::to($payment->transaction->property->agent->user->email)
                ->send(new PaymentReceived($payment));

            // Log successful notification
            Log::info('Payment notifications sent successfully', [
                'payment_id' => $payment->id,
                'transaction_id' => $payment->transaction_id,
                'amount' => $payment->amount
            ]);
        } catch (\Exception $e) {
            // Log notification failure
            Log::error('Failed to send payment notifications', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getPaymentStatus($paymentId)
    {
        return Redis::get("payment:{$paymentId}:status");
    }
}
