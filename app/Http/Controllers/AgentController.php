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
        return Inertia::render("Dashboard/Agent/Index", [
            'agents' => $agents,
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
        $properties = $agent && $agent->properties
            ? $agent->properties->map(function ($property) {
                return [
                    'id' => $property->id,
                    'title' => $property->title,
                    'location' => $property->location,
                    'rent_price' => $property->rent_price,
                    'purchase_price' => $property->purchase_price,
                    'category_name' => $property->category->name ?? 'N/A',
                    'image_url' => $property->image_url,
                    'amenities' => $property->amenities->pluck('name')->join(', '),
                ];
            })
            : [];

        return Inertia::render(
            "Dashboard/Agent/Show",
            [
                'agent' => $agent,
                'properties' => $properties,
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
        $agent->status = 'approved';
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
                'link' => route('agent.dashboard')
            ]
        );

        return redirect()->back()->with('success', 'Agent has been approved successfully.');
    }

    public function reject(Agent $agent)
    {
        $agent->status = 'rejected';
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
