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
    public function confirm(Booking $booking)
    {
        $this->authorizeBooking($booking);

        if (!$booking->status === BookingStatus::Pending) {
            return back()->withErrors(['error' => 'Cette réservation ne peut pas être confirmée.']);
        }

        $booking->update(['status' => BookingStatus::Confirmed]);
        $booking->timeSlot->update(['is_booked' => true]);

        return back()->with('success', 'Réservation confirmée.');
    }

    public function cancel(Booking $booking)
    {
        $this->authorizeBooking($booking);

        if ($booking->status === BookingStatus::Cancelled) {
            return back()->withErrors(['error' => 'Cette réservation est déjà annulée.']);
        }

        $booking->update(['status' => BookingStatus::Cancelled]);

        // Libérer le créneau pour d'autres clients
        $booking->timeSlot->update(['is_booked' => false]);

        return back()->with('success', 'Réservation annulée.');
    }

    // sécurité 

    private function authorizeBooking(Booking $booking): void
    {
        $profileId = auth()->user()->architectProfile->id;

        $belongsToArchitect = $booking
            ->timeSlot
            ->availability
            ->architect_id === $profileId;

        if (!$belongsToArchitect) {
            abort(403);
        }
    }
}
