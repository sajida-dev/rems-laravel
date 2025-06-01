<?php

namespace App\Services;

use App\Events\NewNotification;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use App\Models\HiringRequest;

class NotificationService
{
    public static function createAgentApprovalNotification(User $agent)
    {
        // Notify admin about new agent approval request
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new RealTimeNotification(
                title: 'New Agent Approval Request',
                message: "New agent {$agent->name} is waiting for approval",
                type: 'agent_approval',
                link: route('agent.approval'),
                data: [
                    'agent_id' => $agent->id,
                    'agent_name' => $agent->name
                ]
            ));
        }
    }

    public static function createNewApplicationNotification($application)
    {
        // Notify agent about new application
        if ($application->property->agent) {
            $application->property->agent->user->notify(new RealTimeNotification(
                title: 'New Application Received',
                message: "New {$application->type} application for {$application->property->title}",
                type: 'new_application',
                link: route('application.index'),
                data: [
                    'application_id' => $application->id,
                    'property_id' => $application->property_id
                ]
            ));
        }
    }

    public static function createApplicationStatusNotification($application)
    {
        // Notify user about application status change
        $application->user->notify(new RealTimeNotification(
            title: 'Application Status Updated',
            message: "Your {$application->type} application for {$application->property->title} has been {$application->status}",
            type: 'application_status',
            link: route('application.index'),
            data: [
                'application_id' => $application->id,
                'status' => $application->status
            ]
        ));
    }

    public static function createPaymentNotification($payment)
    {
        // Notify user about payment
        $payment->user->notify(new RealTimeNotification(
            title: 'Payment Required',
            message: "Please complete your payment for {$payment->property->title}",
            type: 'payment',
            link: route('payment.create'),
            data: [
                'payment_id' => $payment->id,
                'property_id' => $payment->property_id
            ]
        ));
    }

    public static function createMessageNotification($message, $recipient)
    {
        // Notify user about new message
        $recipient->notify(new RealTimeNotification(
            title: 'New Message',
            message: "You have a new message from {$message->sender->name}",
            type: 'message',
            link: route('messages.show', $message->conversation_id),
            data: [
                'message_id' => $message->id,
                'conversation_id' => $message->conversation_id
            ]
        ));
    }

    public function notify(User $user, string $type, string $message, array $data = [])
    {
        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'message' => $message,
            'title' => $data['title'] ?? 'Notification',
            'data' => $data
        ]);

        event(new NewNotification($notification));

        return $notification;
    }

    public function notifyHiringRequest(User $agent, HiringRequest $request)
    {
        return $this->notify(
            $agent,
            'hiring_request',
            "New hiring request from {$request->user->name}",
            [
                'title' => 'New Hiring Request',
                'icon' => 'fa-user-plus',
                'link' => route('hiring-requests.show', $request->id)
            ]
        );
    }

    public function notifyHiringRequestStatus(User $user, HiringRequest $request)
    {
        $status = $request->status;
        $icon = $status === 'accepted' ? 'fa-check-circle' : 'fa-times-circle';
        $title = $status === 'accepted' ? 'Hiring Request Accepted' : 'Hiring Request Rejected';

        return $this->notify(
            $user,
            'hiring_request_status',
            "Your hiring request has been {$status} by {$request->agent->user->name}",
            [
                'title' => $title,
                'icon' => $icon,
                'link' => route('hiring-requests.show', $request->id)
            ]
        );
    }
}
