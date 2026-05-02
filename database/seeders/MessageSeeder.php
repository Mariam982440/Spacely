<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $karim = User::where('email', 'karim@spacely.com')->first();
        $sara = User::where('email', 'sara@spacely.com')->first();
        $nadia = User::where('email', 'nadia@spacely.com')->first();
        $omar = User::where('email', 'omar@spacely.com')->first();

        if ($karim && $nadia) {
            $this->seedConversation([
                [$nadia->id, $karim->id, 'Bonjour Karim, je suis interessee par votre travail.', true],
                [$karim->id, $nadia->id, 'Bonjour Nadia, merci beaucoup. Quel type de projet avez-vous ?', true],
                [$nadia->id, $karim->id, 'Une renovation complete de mon appartement a Casablanca.', false],
            ]);
        }

        if ($sara && $omar) {
            $this->seedConversation([
                [$omar->id, $sara->id, 'Bonjour Sara, je cherche a amenager mon bureau a domicile.', true],
                [$sara->id, $omar->id, 'Bonjour Omar, avec plaisir. Quelle est la superficie ?', true],
                [$omar->id, $sara->id, 'Environ 15 m2, avec bureau et rangement.', false],
            ]);
        }
    }

    private function seedConversation(array $messages): void
    {
        foreach ($messages as [$senderId, $receiverId, $body, $isRead]) {
            Message::firstOrCreate(
                [
                    'sender_id' => $senderId,
                    'receiver_id' => $receiverId,
                    'body' => $body,
                ],
                [
                    'is_read' => $isRead,
                    'created_at' => now()->subHours(rand(1, 48)),
                ]
            );
        }
    }
}
