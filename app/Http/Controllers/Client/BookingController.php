<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreBookingRequest;
use App\Models\ArchitectProfile;
use App\Models\Booking;
use App\Models\TimeSlot;
use App\Enums\BookingStatus;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = auth()->user()
            ->clientProfile
            ->bookings()
            ->with([
                'timeSlot.availability.architectProfile.user',
                'quote',
            ])
            ->latest()
            ->paginate(10);

        return view('client.bookings.index', compact('bookings'));
    }

    public function store(StoreBookingRequest $request, ArchitectProfile $profile)
    {
        $slot = TimeSlot::find($request->time_slot_id);

        // vérifier que le créneau appartient bien à cet architecte
        $belongsToArchitect = $slot->availability->architect_id === $profile->id;

        if (!$belongsToArchitect) {
            return back()->with('error', 'Ce créneau ne correspond pas à cet architecte.');
        }

        // vérifier que le créneau est encore libre
        if ($slot->is_booked) {
            return back()->with('error', 'Ce créneau vient d\'être réservé. Veuillez en choisir un autre.');
        }

        Booking::create([
            'client_id'    => auth()->user()->clientProfile->id,
            'time_slot_id' => $slot->id,
            'subject'      => $request->subject,
            'message'      => $request->message,
            'status'       => BookingStatus::Pending,
        ]);

        return redirect()
            ->route('client.bookings.index')
            ->with('success', 'Votre demande de consultation a été envoyée à l\'architecte.');
    }
}