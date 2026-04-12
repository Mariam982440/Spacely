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

    private function generateSlots(Availability $availability, int $durationMinutes): void
    {
        $dayMap = [
            'monday'    => Carbon::MONDAY,
            'tuesday'   => Carbon::TUESDAY,
            'wednesday' => Carbon::WEDNESDAY,
            'thursday'  => Carbon::THURSDAY,
            'friday'    => Carbon::FRIDAY,
            'saturday'  => Carbon::SATURDAY,
            'sunday'    => Carbon::SUNDAY,
        ];

        $dayNumber = $dayMap[$availability->day_of_week];
        $slots     = [];

        // générer pour les 4 prochaines semaines
        for ($week = 0; $week < 4; $week++) {

            $date = Carbon::now()
                ->next($dayNumber)
                ->addWeeks($week);

            $start = Carbon::parse(
                $date->toDateString() . ' ' . $availability->start_time
            );

            $end = Carbon::parse(
                $date->toDateString() . ' ' . $availability->end_time
            );

            // découper la plage en créneaux
            while ($start->copy()->addMinutes($durationMinutes)->lte($end)) {
                $slots[] = [
                    'availability_id' => $availability->id,
                    'start_at'        => $start->copy(),
                    'end_at'          => $start->copy()->addMinutes($durationMinutes),
                    'is_booked'       => false,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];

                $start->addMinutes($durationMinutes);
            }
        }

        // Insérer tous les créneaux en une seule requête
        TimeSlot::insert($slots);
    }

    // sécurité 

    private function authorizeAvailability(Availability $availability): void
    {
        $profileId = auth()->user()->architectProfile->id;

        if ($availability->architect_id !== $profileId) {
            abort(403);
        }
    }
}
