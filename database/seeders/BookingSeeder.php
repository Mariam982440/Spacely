<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClientProfile;
use App\Models\TimeSlot;
use App\Models\Booking;
use App\Models\Quote;
use App\Enums\BookingStatus;
use Illuminate\Support\Str;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $nadia  = ClientProfile::whereHas('user', fn($q) => $q->where('email', 'nadia@spacely.com'))->first();
        $omar   = ClientProfile::whereHas('user', fn($q) => $q->where('email', 'omar@spacely.com'))->first();
        $leila  = ClientProfile::whereHas('user', fn($q) => $q->where('email', 'leila@spacely.com'))->first();

        // ── Scénario 1 : Réservation confirmée + devis accepté ──
        $slot1 = TimeSlot::where('is_booked', false)->first();

        if ($slot1 && $nadia) {
            $booking1 = Booking::firstOrCreate(
                ['time_slot_id' => $slot1->id],
                [
                    'client_id'    => $nadia->id,
                    'time_slot_id' => $slot1->id,
                    'subject'      => 'Rénovation salon et cuisine ouverte',
                    'message'      => 'Bonjour, je souhaite transformer mon appartement de 80m². Budget autour de 150 000 MAD.',
                    'status'       => BookingStatus::Confirmed,
                ]
            );
            $slot1->update(['is_booked' => true]);

            // Devis associé
            if (!$booking1->quote) {
                $totalHt  = 12500.00;
                $tva      = 20.00;
                $totalTtc = $totalHt * (1 + $tva / 100);

                $quote = Quote::create([
                    'booking_id' => $booking1->id,
                    'reference'  => 'QUO-' . strtoupper(Str::random(8)),
                    'total_ht'   => $totalHt,
                    'tva'        => $tva,
                    'total_ttc'  => $totalTtc,
                    'status'     => 'accepted',
                ]);

                $quote->items()->createMany([
                    ['description' => 'Consultation initiale et plans 3D', 'quantity' => 1,  'unit_price' => 2500.00],
                    ['description' => 'Suivi chantier (10 visites)',        'quantity' => 10, 'unit_price' => 500.00],
                    ['description' => 'Sélection mobilier et matériaux',    'quantity' => 1,  'unit_price' => 2500.00],
                    ['description' => 'Coordination entreprises',            'quantity' => 1,  'unit_price' => 5000.00],
                    ['description' => 'Livraison dossier final',             'quantity' => 1,  'unit_price' => 2000.00],
                ]);
            }
        }

        // ── Scénario 2 : Réservation en attente ──
        $slot2 = TimeSlot::where('is_booked', false)->skip(1)->first();

        if ($slot2 && $omar) {
            Booking::firstOrCreate(
                ['time_slot_id' => $slot2->id],
                [
                    'client_id'    => $omar->id,
                    'time_slot_id' => $slot2->id,
                    'subject'      => 'Aménagement bureau à domicile',
                    'message'      => 'Je cherche à créer un espace de travail professionnel dans ma chambre d\'amis.',
                    'status'       => BookingStatus::Pending,
                ]
            );
        }

        // ── Scénario 3 : Réservation annulée ──
        $slot3 = TimeSlot::where('is_booked', false)->skip(2)->first();

        if ($slot3 && $leila) {
            Booking::firstOrCreate(
                ['time_slot_id' => $slot3->id],
                [
                    'client_id'    => $leila->id,
                    'time_slot_id' => $slot3->id,
                    'subject'      => 'Décoration chambre enfant',
                    'message'      => 'Pour ma fille de 5 ans, je veux quelque chose de magique.',
                    'status'       => BookingStatus::Cancelled,
                ]
            );
        }

        // ── Scénario 4 : Réservation confirmée sans devis (pour tester la création) ──
        $slot4 = TimeSlot::where('is_booked', false)->skip(5)->first();

        if ($slot4 && $nadia) {
            $booking4 = Booking::firstOrCreate(
                ['time_slot_id' => $slot4->id],
                [
                    'client_id'    => $nadia->id,
                    'time_slot_id' => $slot4->id,
                    'subject'      => 'Rénovation salle de bain',
                    'message'      => 'Salle de bain de 8m² à refaire entièrement.',
                    'status'       => BookingStatus::Confirmed,
                ]
            );
            $slot4->update(['is_booked' => true]);
        }
    }
}