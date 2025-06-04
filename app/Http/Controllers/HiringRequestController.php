<?php

namespace App\Http\Controllers;

use App\Models\HiringRequest;
use App\Http\Requests\StoreHiringRequestRequest;
use App\Http\Requests\UpdateHiringRequestRequest;
use App\Mail\HiringRequestNotification;
use App\Mail\HiringRequestReject;
use App\Mail\HiringRequestStatusUpdate;
use App\Models\Agent;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use App\Mail\NewHiringRequestNotification;
use App\Mail\HiringRequestStatusUpdated;
use App\Services\EmailNotificationService;
use App\Services\NotificationService;

class HiringRequestController extends Controller
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
        $query = HiringRequest::with(['user', 'agent.user']);

        $authUser = auth()->user();
        $agent = $authUser->agent;

        // Get counts for different statuses
        $counts = [
            'total' => 0,
            'pending' => 0,
            'accepted' => 0,
            'rejected' => 0
        ];

        if ($authUser->role == 'agent') {
            $query->where('agent_id', $agent->id);

            // Get counts for agent's hiring requests
            $counts['total'] = HiringRequest::where('agent_id', $agent->id)->count();
            $counts['pending'] = HiringRequest::where('agent_id', $agent->id)->where('status', 'pending')->count();
            $counts['accepted'] = HiringRequest::where('agent_id', $agent->id)->where('status', 'accepted')->count();
            $counts['rejected'] = HiringRequest::where('agent_id', $agent->id)->where('status', 'rejected')->count();
        } elseif ($authUser->role == 'user') {
            $query->where('user_id', $authUser->id);

            // Get counts for user's hiring requests
            $counts['total'] = HiringRequest::where('user_id', $authUser->id)->count();
            $counts['pending'] = HiringRequest::where('user_id', $authUser->id)->where('status', 'pending')->count();
            $counts['accepted'] = HiringRequest::where('user_id', $authUser->id)->where('status', 'accepted')->count();
            $counts['rejected'] = HiringRequest::where('user_id', $authUser->id)->where('status', 'rejected')->count();
        } else {
            // Admin sees all hiring requests
            $counts['total'] = HiringRequest::count();
            $counts['pending'] = HiringRequest::where('status', 'pending')->count();
            $counts['accepted'] = HiringRequest::where('status', 'accepted')->count();
            $counts['rejected'] = HiringRequest::where('status', 'rejected')->count();
        }

        if ($request->filled('global')) {
            $search = $request->global;
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('location', 'LIKE', "%{$search}%")
                    ->orWhere('requirements', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%");
            });
        }

        // Sorting
        if ($request->filled('sortBy') && $request->filled('sortDesc')) {
            $direction = $request->sortDesc === 'true' ? 'desc' : 'asc';
            $query->orderBy($request->sortBy, $direction);
        } else {
            $query->latest('created_at');
        }

        // Pagination
        $perPage = $request->input('perPage', 10);
        $page = $request->input('page', 1);

        $hiringRequests = $query->paginate($perPage, ['*'], 'page', $page)
            ->appends($request->all());

        return Inertia::render('Dashboard/Hiring-Request/Index', [
            'hiringRequests' => $hiringRequests,
            'counts' => $counts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * to fill request form by user for hiring agent
     */
    public function create($id)
    {
        $categories = Category::all(['id', 'name'])->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
            ];
        });
        return Inertia::render(
            "Public/Hiring-Request/Create",
            [
                'categories' => $categories,
                'agent_id' => $id,
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHiringRequestRequest $request)
    {
        $hiringRequest = HiringRequest::create([
            'user_id' => auth()->id(),
            'agent_id' => $request->agent_id,
            'request_type' => $request->request_type,
            'category_id' => $request->category_id,
            'location' => $request->location,
            'min_budget' => $request->min_budget,
            'max_budget' => $request->max_budget,
            'bedrooms' => $request->bedrooms,
            'requirements' => $request->requirements,
            'status' => 'pending'
        ]);

        $agent = Agent::with('user')->findOrFail($request->agent_id);

        // Send email notification using service
        $this->emailService->sendHiringRequestNotification($hiringRequest);

        // Send real-time notification
        $notificationService = new NotificationService();
        $notificationService->notify(
            $agent->user,
            'new_hiring_request',
            "New hiring request from " . auth()->user()->name,
            [
                'title' => 'New Hiring Request',
                'icon' => 'fa-user-plus',
                'link' => route('hiring-requests.show', $hiringRequest->id)
            ]
        );

        return redirect()->route('hiring-requests.index')->with('success', 'Hiring request sent successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(HiringRequest $hiringRequest)
    {
        $hiringRequest->load(['user', 'agent.user', 'category']);
        return Inertia::render('Dashboard/Hiring-Request/Show', [
            'hiringRequest' => $hiringRequest,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HiringRequest $hiringRequest)
    {
        $hiringRequest->load(['user', 'agent.user', 'category']);
        return Inertia::render('Dashboard/Hiring-Request/Edit', [
            'hiringRequest' => $hiringRequest,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HiringRequest $hiringRequest)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected'
        ]);

        $hiringRequest->update([
            'status' => $request->status
        ]);

        // Send email notification
        Mail::to($hiringRequest->user->email)->send(new HiringRequestStatusUpdate($hiringRequest));

        // Send real-time notification
        $notificationService = new \App\Services\NotificationService();
        $notificationService->notifyHiringRequestStatus($hiringRequest->user, $hiringRequest);

        return redirect()->back()->with('success', 'Hiring request status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HiringRequest $hiringRequest)
    {
        //
    }

    public function approve(HiringRequest $hiringRequest)
    {
        $hiringRequest->status = 'accepted';
        $hiringRequest->save();

        // Send notification using service
        $this->emailService->sendHiringRequestStatusUpdate($hiringRequest);

        // Send real-time notification
        $notificationService = new NotificationService();
        $notificationService->notify(
            $hiringRequest->user,
            'hiring_request_status',
            "Your hiring request has been accepted",
            [
                'title' => 'Hiring Request Accepted',
                'icon' => 'fa-check-circle',
                'link' => route('hiring-requests.show', $hiringRequest->id)
            ]
        );

        return redirect()->back()->with('success', 'Hiring request has been accepted and the user has been notified.');
    }

    public function reject(HiringRequest $hiringRequest)
    {
        $hiringRequest->status = 'rejected';
        $hiringRequest->save();

        // Send notification using service
        $this->emailService->sendHiringRequestRejection($hiringRequest);

        // Send real-time notification
        $notificationService = new NotificationService();
        $notificationService->notify(
            $hiringRequest->user,
            'hiring_request_status',
            "Your hiring request has been rejected",
            [
                'title' => 'Hiring Request Rejected',
                'icon' => 'fa-times-circle',
                'link' => route('hiring-requests.show', $hiringRequest->id)
            ]
        );

        return redirect()->back()->with('success', 'Hiring request has been rejected and the user has been notified.');
    }
}
