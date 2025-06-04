<?php

namespace App\Services;

use App\Models\Application;
use App\Models\HiringRequest;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\PropertyApplicationNotification;
use App\Mail\HiringRequestNotification;
use App\Mail\AgentApprovedMail;
use App\Mail\ApplicationStatusUpdated;
use App\Mail\HiringRequestStatusUpdate;
use App\Mail\HiringRequestReject;

class EmailNotificationService
{
    public function sendPropertyApplicationNotification(Application $application)
    {
        // Notify agent
        Mail::to($application->property->agent->user->email)
            ->queue(new PropertyApplicationNotification($application));
    }

    public function sendHiringRequestNotification(HiringRequest $hiringRequest)
    {
        // Notify agent
        Mail::to($hiringRequest->agent->user->email)
            ->queue(new HiringRequestNotification($hiringRequest));
    }

    public function sendAgentApprovalNotification(User $agent, string $username, string $password)
    {
        Mail::to($agent->email)
            ->queue(new AgentApprovedMail($username, $password));
    }

    public function sendApplicationStatusUpdate(Application $application)
    {
        Mail::to($application->user->email)
            ->queue(new ApplicationStatusUpdated($application));
    }

    public function sendHiringRequestStatusUpdate(HiringRequest $hiringRequest)
    {
        Mail::to($hiringRequest->user->email)
            ->queue(new HiringRequestStatusUpdate($hiringRequest));
    }

    public function sendHiringRequestRejection(HiringRequest $hiringRequest)
    {
        Mail::to($hiringRequest->user->email)
            ->queue(new HiringRequestReject($hiringRequest));
    }
}
