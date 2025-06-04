<?php

namespace App\Http\Controllers;

use App\Helpers\UserHelper;
use App\Models\Agent;
use App\Http\Requests\StoreAgentRequest;
use App\Http\Requests\UpdateAgentRequest;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Mail\AgentApprovedMail;
use Illuminate\Support\Facades\Mail;
use App\Services\EmailNotificationService;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Hash;
use App\Models\Property;
use App\Models\Application;
use App\Models\HiringRequest;
use App\Models\Transaction;

class AgentController extends Controller
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
        $query = User::with(['agent'])
            ->where('role', 'agent');


        if ($request->filled('global')) {
            $search = $request->global;

            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('contact', 'LIKE', "%{$search}%");

                $q->orWhereHas('agent', function ($agentQuery) use ($search) {
                    $agentQuery->where('licence_no', 'LIKE', "%{$search}%")
                        ->orWhere('agency', 'LIKE', "%{$search}%")
                        ->orWhere('experience', 'LIKE', "%{$search}%")
                        ->orWhere('bio', 'LIKE', "%{$search}%");
                });
            });
        }

        if ($request->filled('sortBy')) {
            $direction = $request->sortBy === 'true' ? 'desc' : 'asc';
            $sortField = $request->get('sortField', 'created_at');
            $query->orderBy($sortField, $direction);
        } else {
            $query->latest('created_at');
        }

        $perPage = $request->perPage ?? 10;
        $page = $request->page ?? 1;

        $agents = $query->paginate($perPage, ['*'], 'page', $page)
            ->appends($request->all());

        // Get stats
        $stats = [
            'totalAgents' => User::where('role', 'agent')->count(),
            'pendingAgents' => User::whereHas('agent', function ($q) {
                $q->where('status', 0);
            })->where('role', 'agent')->count(),
            'approvedAgents' => User::whereHas('agent', function ($q) {
                $q->where('status', 1);
            })->where('role', 'agent')->count(),
            'rejectedAgents' => User::whereHas('agent', function ($q) {
                $q->where('status', 2);
            })->where('role', 'agent')->count(),
            'totalProperties' => Property::whereHas('agent')->count(),
            'totalApplications' => Application::whereHas('property.agent')->count(),
            'totalHiringRequests' => HiringRequest::whereHas('agent')->count(),
            'totalTransactions' => Transaction::whereHas('property.agent')->count(),
        ];

        return Inertia::render("Dashboard/Agent/Index", [
            'agents' => $agents,
            'stats' => $stats,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all(['id', 'name'])->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
            ];
        });
        return Inertia::render("Dashboard/Agent/Create", ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAgentRequest $request)
    {
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath =  $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'agent',
            'contact' => $request->contact,
            'password' => bcrypt('password'),
            'profile_photo_path' => $avatarPath,
        ]);


        $agent = Agent::create([
            'user_id' => $user->id,
            'licence_no' => $request->licence_no,
            'agency' => $request->agency,
            'experience' => $request->experience,
            'bio' => $request->bio,
            'status' => $request->status ?? 0,

        ]);

        $agent->categories()->attach($request->categories);

        return redirect()->route("agents.index")->with("success", "Agent inserted successfully.");
    }
    /**
     * Display the specified resource.
     */
    public function show(Agent $agent)
    {
        $agent->load([
            'user',
            'categories',
            'properties.category',
            'properties.amenities',
            'properties.uploads',
        ]);

        // Get counts
        $counts = [
            'properties' => $agent->properties()->count(),
            'applications' => Application::whereHas('property', function ($query) use ($agent) {
                $query->where('agent_id', $agent->id);
            })->count(),
            'hiringRequests' => HiringRequest::where('agent_id', $agent->id)->count(),
            'transactions' => Transaction::whereHas('property', function ($query) use ($agent) {
                $query->where('agent_id', $agent->id);
            })->count(),
        ];

        // Get paginated data based on the active tab
        $properties = $agent->properties()
            ->with(['category', 'amenities', 'uploads'])
            ->latest()
            ->get()
            ->map(function ($property) {
                return [
                    'id' => $property->id,
                    'title' => $property->title,
                    'price' => $property->type === 'rent' ? $property->rent_price : $property->purchase_price,
                    'status' => $property->status,
                    'created_at' => $property->created_at,
                    'location' => $property->location,
                    'type' => $property->type,
                    'bedrooms' => $property->bedrooms,
                    'bathrooms' => $property->bathrooms,
                    'garage' => $property->garage,
                    'lot_area' => $property->lot_area,
                    'floor_area' => $property->floor_area,
                    'year_built' => $property->year_built,
                    'category' => $property->category->name,
                    'amenities' => $property->amenities->pluck('name')->join(', '),
                    'description' => $property->description,
                    'image_url' => $property->image_url,
                ];
            });

        $applications = Application::whereHas('property', function ($query) use ($agent) {
            $query->where('agent_id', $agent->id);
        })
            ->with(['property', 'user'])
            ->latest()
            ->get()
            ->map(function ($application) {
                return [
                    'id' => $application->id,
                    'property_title' => $application->property->title,
                    'type' => $application->type,
                    'status' => $application->status,
                    'created_at' => $application->created_at,
                    'user' => $application->user->name,
                    'user_email' => $application->user->email,
                    'user_contact' => $application->user->contact,
                    'property_location' => $application->property->location,
                    'property_type' => $application->property->type,
                    'property_price' => $application->property->type === 'rent' ?
                        $application->property->rent_price :
                        $application->property->purchase_price,
                ];
            });

        $hiringRequests = HiringRequest::where('agent_id', $agent->id)
            ->latest()
            ->get()
            ->map(function ($request) {
                return [
                    'id' => $request->id,
                    'request_type' => $request->request_type,
                    'location' => $request->location,
                    'status' => $request->status,
                    'created_at' => $request->created_at,
                    'description' => $request->description,
                    'budget' => $request->budget,
                    'timeline' => $request->timeline,
                    'additional_requirements' => $request->additional_requirements,
                ];
            });

        $transactions = Transaction::whereHas('property', function ($query) use ($agent) {
            $query->where('agent_id', $agent->id);
        })
            ->with(['property', 'user'])
            ->latest()
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'amount' => $transaction->amount,
                    'payment_method' => $transaction->payment_method,
                    'status' => $transaction->status,
                    'created_at' => $transaction->created_at,
                    'user' => $transaction->user->name,
                    'user_email' => $transaction->user->email,
                    'property_title' => $transaction->property->title,
                    'property_location' => $transaction->property->location,
                    'transaction_type' => $transaction->transaction_type,
                    'reference_number' => $transaction->reference_number,
                ];
            });

        return Inertia::render(
            "Dashboard/Agent/Show",
            [
                'agent' => $agent,
                'properties' => [
                    'data' => $properties,
                    'total' => $properties->count(),
                    'per_page' => 10,
                    'current_page' => 1,
                    'last_page' => 1
                ],
                'applications' => [
                    'data' => $applications,
                    'total' => $applications->count(),
                    'per_page' => 10,
                    'current_page' => 1,
                    'last_page' => 1
                ],
                'hiringRequests' => [
                    'data' => $hiringRequests,
                    'total' => $hiringRequests->count(),
                    'per_page' => 10,
                    'current_page' => 1,
                    'last_page' => 1
                ],
                'transactions' => [
                    'data' => $transactions,
                    'total' => $transactions->count(),
                    'per_page' => 10,
                    'current_page' => 1,
                    'last_page' => 1
                ],
                'counts' => $counts,
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agent $agent)
    {
        $categories = Category::all(['id', 'name'])->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
            ];
        });
        $agent->load(['user', 'categories']);
        return Inertia::render(
            "Dashboard/Agent/Edit",
            [
                'agent' => $agent,
                'categories' => $categories
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAgentRequest $request, Agent $agent)
    {
        // dd($request);
        $validated = $request->validated();
        $agent->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);
        $agent->update([
            'agency' => $validated['agency'],
            'licence_no' => $validated['licence_no'],
            'contact' => $validated['contact'],
            'experience' => $validated['experience'],
            'bio' => $validated['bio'],
            'status' => $validated['status'] ?? false,
        ]);
        if ($request->hasFile('avatar')) {
            $agent->user->updateProfilePhoto($request['avatar']);
        }

        $agent->categories()->sync($validated['categories'] ?? []);

        return redirect()->route("agents.index")->with("success", "Agents updated Successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agent $agent)
    {
        $agent->delete();
        return redirect()->route("agents.index")->with("success", "Agents deleted Successfully.");
    }

    public function approve(Agent $agent)
    {
        $agent->status = 1; // 1 for approved
        $agent->save();

        // Generate username and password
        $username = UserHelper::generateUniqueUsername($agent->user->name, $agent->user->email);
        $password = UserHelper::generateRandomPassword(12);

        // Update user credentials
        $agent->user->username = $username;
        $agent->user->password = Hash::make($password);
        $agent->user->save();

        // Send email notification using service
        $this->emailService->sendAgentApprovalNotification($agent->user, $username, $password);

        // Send real-time notification
        $notificationService = new NotificationService();
        $notificationService->notify(
            $agent->user,
            'agent_status',
            "Your agent account has been approved",
            [
                'title' => 'Agent Account Approved',
                'icon' => 'fa-check-circle',
                'link' => route('agents.index')
            ]
        );

        return redirect()->back()->with('success', 'Agent has been approved successfully.');
    }

    public function reject(Agent $agent)
    {
        $agent->status = 2; // 2 for rejected
        $agent->save();

        // Send real-time notification
        $notificationService = new NotificationService();
        $notificationService->notify(
            $agent->user,
            'agent_status',
            "Your agent account has been rejected",
            [
                'title' => 'Agent Account Rejected',
                'icon' => 'fa-times-circle',
                'link' => route('agent.profile')
            ]
        );

        return redirect()->back()->with('success', 'Agent has been rejected.');
    }
}
