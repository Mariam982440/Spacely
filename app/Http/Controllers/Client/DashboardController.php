<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ArchitectProfile;
use App\Models\BlogPost;
use App\Models\Message;
use App\Enums\BookingStatus;

class DashboardController extends Controller
{
    public function index()
    {
        $clientProfile = auth()->user()->clientProfile;

        // réservations récentes
        $bookings = $clientProfile->bookings()
            ->with('timeSlot', 'timeSlot.availability.architectProfile.user')
            ->latest()
            ->take(3)
            ->get();

        // devis en attente de réponse
        $pendingQuotes = $clientProfile->bookings()
            ->whereHas('quote', fn($q) => $q->where('status', 'sent'))
            ->with('quote', 'timeSlot.availability.architectProfile.user')
            ->count();

        // messages non lus
        $unreadMessages = Message::where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->count();

        // architectes mis en avant (vérifiés)
        $featuredArchitects = ArchitectProfile::where('is_verified', true)
            ->with('user', 'projects.images')
            ->withCount('projects')
            ->inRandomOrder()
            ->take(3)
            ->get();

        // articles récents
        $recentPosts = BlogPost::where('status', 'published')
            ->with('architectProfile.user')
            ->latest()
            ->take(3)
            ->get();

        return view('client.dashboard', compact(
            'bookings',
            'pendingQuotes',
            'unreadMessages',
            'featuredArchitects',
            'recentPosts'
        ));
    }
}