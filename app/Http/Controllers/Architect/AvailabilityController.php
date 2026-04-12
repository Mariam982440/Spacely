<?php

namespace App\Http\Controllers\Architect;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Carbon\Carbon;
class AvailabilityController extends Controller
{
    public function index()
    {
        $availabilities = auth()->user()
            ->architectProfile
            ->availabilities()
            ->withCount('timeSlots')
            ->orderByRaw("FIELD(day_of_week,
                'monday','tuesday','wednesday',
                'thursday','friday','saturday','sunday'
            )")
            ->get();

        return view('architect.availabilities.index', compact('availabilities'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
            'slot_duration' => 'required|integer|in:30,60,90,120',
        ]);

        $profile = auth()->user()->architectProfile;

        // vérifier qu'il n'existe pas déjà une dispo ce jour
        $exists = $profile->availabilities()
            ->where('day_of_week', $request->day_of_week)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'day_of_week' => 'Vous avez déjà configuré ce jour.',
            ]);
        }

        // créer la disponibilité
        $availability = $profile->availabilities()->create([
            'day_of_week' => $request->day_of_week,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
        ]);

        // générer les créneaux pour les 4 prochaines semaines
        $this->generateSlots($availability, $request->slot_duration);

        return back()->with('success', 'Disponibilité ajoutée avec succès.');
    }

    public function destroy(Availability $availability)
    {
        $this->authorizeAvailability($availability);

        // supprimer uniquement les créneaux non réservés
        $availability->timeSlots()
            ->where('is_booked', false)
            ->delete();

        $availability->delete();

        return back()->with('success', 'Disponibilité supprimée.');
    }
}
