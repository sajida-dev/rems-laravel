<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BookmarkController extends Controller
{
    /**
     * Display a listing of the user's bookmarked properties.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Base query with relationships
        $query = Property::with(['category', 'agent.user'])
            ->withCount('bookmark');

        // Different queries based on user role
        if ($user->role === 'admin') {
            // Admin sees all properties
            $query->latest();
        } elseif ($user->role === 'agent') {
            // Agent sees only their properties
            $query->where('agent_id', $user->agent->id)
                ->latest();
        } else {
            // Regular users see only their bookmarked properties
            $query->whereHas('bookmark', function ($query) {
                $query->where('user_id', Auth::id());
            })
                ->with(['bookmark' => function ($query) {
                    $query->where('user_id', Auth::id());
                }])
                ->latest();
        }

        // Global search
        if ($request->filled('global')) {
            $search = $request->global;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhereHas('agent.user', fn($q) => $q->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('category', fn($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        // Sorting
        if ($request->filled('sortBy')) {
            $direction = $request->sortDesc === 'true' ? 'desc' : 'asc';
            $query->orderBy($request->sortBy, $direction);
        }

        // Pagination
        $perPage = $request->perPage ?? 10;
        $properties = $query->paginate($perPage)->appends($request->all());

        return Inertia::render('Dashboard/Bookmarks/Index', [
            'properties' => $properties,
            'filters' => $request->only(['global', 'sortBy', 'sortDesc', 'perPage']),
            'userRole' => $user->role
        ]);
    }

    /**
     * Display bookmarks for admin/agent view
     */
    public function adminIndex()
    {
        $properties = Property::withCount('bookmark')
            ->with(['category', 'agent.user'])
            ->when(Auth::user()->role === 'agent', function ($query) {
                return $query->where('agent_id', Auth::user()->agent->id);
            })
            ->latest()
            ->paginate(12);

        return Inertia::render('Dashboard/Bookmarks/AdminIndex', [
            'properties' => $properties
        ]);
    }

    /**
     * Show users who bookmarked a specific property
     */
    public function show(Property $property)
    {
        // Check if the user has bookmarked this property
        $hasBookmarked = $property->bookmark()
            ->where('user_id', Auth::id())
            ->exists();

        // Allow if user is admin/agent or has bookmarked the property
        if (!in_array(Auth::user()->role, ['admin', 'agent']) && !$hasBookmarked) {
            abort(403, 'Unauthorized action.');
        }

        $bookmarkedUsers = $property->bookmark()
            ->with(['user' => function ($query) {
                $query->select('id', 'name', 'email', 'profile_photo_path');
            }])
            ->latest()
            ->paginate(10);

        return Inertia::render('Dashboard/Bookmarks/Show', [
            'property' => $property->load(['category', 'agent.user']),
            'bookmarkedUsers' => $bookmarkedUsers
        ]);
    }

    /**
     * Toggle bookmark status for a property.
     */
    public function toggle(Property $property)
    {
        $bookmark = Bookmark::where('user_id', Auth::id())
            ->where('property_id', $property->id)
            ->first();
        if ($bookmark) {
            $bookmark->delete();
            return redirect()->back()->with('success', 'Property removed from bookmarks!');
        }

        Bookmark::create([
            'user_id' => Auth::id(),
            'property_id' => $property->id
        ]);

        return redirect()->back()->with('success', 'Property added to bookmarks!');
    }

    /**
     * Remove a bookmark.
     */
    public function destroy(Property $property)
    {
        $deleted = Bookmark::where('user_id', Auth::id())
            ->where('property_id', $property->id)
            ->delete();

        return redirect()->back()->with('success', 'Property removed from bookmarks!');
    }
}
