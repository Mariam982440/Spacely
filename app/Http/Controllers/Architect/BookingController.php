<?php

namespace App\Http\Controllers\Architect;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Enums\BookingStatus;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::query()
            ->whereHas('timeSlot.availability', function ($q) {
                $q->where('architect_id', auth()->user()->architectProfile->id);
            })
            ->with('timeSlot', 'clientProfile.user')
            ->latest();

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(10);

        return view('architect.bookings.index', compact('bookings'));
    }
}
