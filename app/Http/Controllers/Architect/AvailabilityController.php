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
}
