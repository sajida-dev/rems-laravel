<?php

namespace App\Http\Controllers;

use App\Mail\ApplicationStatusUpdated;
use App\Mail\PropertyApplicationNotification;
use App\Models\Application;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use App\Services\NotificationService;
use App\Services\EmailNotificationService;

class ApplicationController extends Controller
{
    protected $emailService;

    public function __construct(EmailNotificationService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $perPage = $request->perPage ?? 10;
        $page = $request->page ?? 1;

        $query = Application::with(['user', 'property.agent.user']);

        if ($user->role === 'agent') {
            $agent = $user->agent;

            $query->whereHas('property', function ($q) use ($agent) {
                $q->where('agent_id', $agent->id);
            });
        } elseif ($user->role === 'user') {
            $query->where('user_id', $user->id);
        }

        $applications = $query->latest()
            ->paginate($perPage, ['*'], 'page', $page)
            ->appends($request->except('page'));

        return Inertia::render("Dashboard/Application/Index", [
            'applications' => $applications,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'type' => 'required|in:rent,buy'
        ]);

        $user = auth()->user();
        $property = Property::with('agent.user')->findOrFail($request->property_id);

        $application = Application::create([
            'user_id' => auth()->id(),
            'property_id' => $request->property_id,
            'type' => $request->type,
            'status' => 'pending'
        ]);

        if ($property->agent && $property->agent->user) {
            // Send email notification using the service
            $this->emailService->sendPropertyApplicationNotification($application);

            // Send real-time notification to agent
            $notificationService = new NotificationService();
            $notificationService->notify(
                $property->agent->user,
                'new_application',
                "New {$request->type} application for {$property->title} from {$user->name}",
                [
                    'title' => 'New Property Application',
                    'icon' => 'fa-file-alt',
                    'link' => route('application.index')
                ]
            );
        }

        return redirect()->back()->with('success', 'Application sent. Await confirmation.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Application $application)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Application $application)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        if ($request->status === 'approved') {
            $existingApproved = Application::where('property_id', $application->property_id)
                ->where('status', 'approved')
                ->where('id', '!=', $application->id)
                ->exists();

            if ($existingApproved) {
                return redirect()->back()->with('error', 'Another application for this property has already been approved.');
            }
        }

        DB::beginTransaction();

        try {
            $application->update([
                'status' => $request->status,
            ]);

            $application->load(['user', 'property']);

            // Send notification email using the service
            $this->emailService->sendApplicationStatusUpdate($application);

            // Send real-time notification to user
            $notificationService = new NotificationService();
            $notificationService->notify(
                $application->user,
                'application_status',
                "Your {$application->type} application for {$application->property->title} has been {$application->status}",
                [
                    'title' => 'Application Status Updated',
                    'icon' => $application->status === 'approved' ? 'fa-check-circle' : 'fa-times-circle',
                    'link' => route('application.index')
                ]
            );

            DB::commit();

            return redirect()->back()->with('success', 'Application status updated and user notified.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update application status', [
                'error' => $e->getMessage(),
                'application_id' => $application->id,
            ]);

            return redirect()->back()->with('error', 'Something went wrong while updating the status.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Application $application)
    {
        //
    }
}
