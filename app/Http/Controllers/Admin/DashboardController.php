<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArchitectProfile;
use App\Models\Booking;
use App\Models\Project;
use App\Models\Quote;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'clients' => User::whereHas('role', fn($query) => $query->where('slug', 'client'))->count(),
            'architects' => ArchitectProfile::count(),
            'pendingArchitects' => ArchitectProfile::where('is_verified', false)->count(),
            'projects' => Project::count(),
            'bookings' => Booking::count(),
            'quotes' => Quote::count(),
            'acceptedQuotes' => Quote::where('status', 'accepted')->count(),
        ];

        $latestArchitects = ArchitectProfile::with('user')
            ->latest()
            ->take(5)
            ->get();

        $latestBookings = Booking::with('clientProfile.user', 'timeSlot.availability.architectProfile.user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestArchitects', 'latestBookings'));
    }
}
