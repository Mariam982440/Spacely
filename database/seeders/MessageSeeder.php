<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Message;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $karim = User::where('email', 'karim@spacely.com')->first();
        $sara  = User::where('email', 'sara@spacely.com')->first();
        $nadia = User::where('email', 'nadia@spacely.com')->first();
        $omar  = User::where('email', 'omar@spacely.com')->first();

        // conversation Nadia - Karim 
        $conv1 = [
            [$nadia->id, $karim->id, 'Bonjour Karim, j\'ai vu vos réalisations et je suis très impressionnée. Seriez-vous disponible pour discuter de mon projet ?', true],
            [$karim->id, $nadia->id, 'Bonjour Nadia, merci beaucoup ! Je serais ravi d\'en discuter. De quel type de projet s\'agit-il ?', true],
            [$nadia->id, $karim->id, 'Il s\'agit de la rénovation complète de mon appartement à Casablanca, environ 120m². Je veux un style contemporain et épuré.', true],
            [$karim->id, $nadia->id, 'Parfait, c\'est exactement mon domaine de prédilection. Avez-vous déjà réservé un créneau de consultation via la plateforme ?', true],
            [$nadia->id, $karim->id, 'Oui, je viens de le faire ! À bientôt.', false],
        ];

        foreach ($conv1 as [$senderId, $receiverId, $body, $isRead]) {
            Message::firstOrCreate(
                ['sender_id' => $senderId, 'receiver_id' => $receiverId, 'body' => $body],
                ['is_read' => $isRead, 'created_at' => now()->subHours(rand(1, 48))]
            );
        }

        // conversation Omar - Sara 
        $conv2 = [
            [$omar->id, $sara->id, 'Bonjour Sara, je cherche quelqu\'un pour aménager mon bureau à domicile en style scandinave. Vous spécialisez-vous dans ce style ?', true],
            [$sara->id, $omar->id, 'Bonjour Omar ! Oui, c\'est l\'un de mes styles favoris. Le scandinave c\'est fonctionnel, chaleureux et très zen. Quelle superficie ?', true],
            [$omar->id, $sara->id, 'Une chambre convertie d\'environ 15m². J\'ai besoin d\'un bureau, une bibliothèque et un espace de réunion pour 2-3 personnes.', false],
        ];

        foreach ($conv2 as [$senderId, $receiverId, $body, $isRead]) {
            Message::firstOrCreate(
                ['sender_id' => $senderId, 'receiver_id' => $receiverId, 'body' => $body],
                ['is_read' => $isRead, 'created_at' => now()->subHours(rand(1, 24))]
            );
        }
    }
}