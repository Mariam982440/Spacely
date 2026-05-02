<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ArchitectProfile;
use App\Models\Availability;
use App\Models\TimeSlot;
use Carbon\Carbon;

class AvailabilitySeeder extends Seeder
{
    public function run(): void
    {
        $architects = ArchitectProfile::with('user')->get();

        $schedules = [
            'karim@spacely.com'   => ['monday', 'wednesday', 'friday'],
            'sara@spacely.com'    => ['tuesday', 'thursday', 'saturday'],
            'youssef@spacely.com' => ['monday', 'tuesday', 'thursday'],
        ];

        foreach ($architects as $profile) {
            $email = $profile->user->email;

            if (!isset($schedules[$email])) continue;

            foreach ($schedules[$email] as $day) {

                // Éviter les doublons
                $existing = Availability::where('architect_id', $profile->id)
                    ->where('day_of_week', $day)
                    ->first();

                if ($existing) continue;

                $availability = Availability::create([
                    'architect_id' => $profile->id,
                    'day_of_week'  => $day,
                    'start_time'   => '09:00:00',
                    'end_time'     => '17:00:00',
                ]);

                // Générer les créneaux d'1h pour les 4 prochaines semaines
                $this->generateSlots($availability, 60);
            }
        }
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

        for ($week = 0; $week < 4; $week++) {

            $date = Carbon::now()->next($dayNumber)->addWeeks($week);

            $start = Carbon::parse($date->toDateString() . ' ' . $availability->start_time);
            $end   = Carbon::parse($date->toDateString() . ' ' . $availability->end_time);

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

        TimeSlot::insert($slots);
    }
}