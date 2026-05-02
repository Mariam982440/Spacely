<?php

namespace Database\Seeders;

use App\Models\ArchitectProfile;
use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $karim = ArchitectProfile::whereHas('user', fn($q) => $q->where('email', 'karim@spacely.com'))->first();
        $sara = ArchitectProfile::whereHas('user', fn($q) => $q->where('email', 'sara@spacely.com'))->first();

        $posts = [
            [
                'architect' => $karim,
                'title' => '5 tendances decoration interieure a adopter en 2025',
                'content' => "Le design interieur evolue constamment, et 2025 ne fait pas exception.\n\n1. Le minimalisme chaleureux\n2. Les matieres naturelles\n3. Le coin lecture\n4. Les arches\n5. Le vert en toutes nuances",
                'status' => 'published',
            ],
            [
                'architect' => $karim,
                'title' => 'Comment optimiser la lumiere naturelle dans votre appartement',
                'content' => "La lumiere naturelle est le premier materiau d'un architecte d'interieur. Miroirs strategiques, couleurs claires, rideaux legers et plan ouvert peuvent transformer un espace.",
                'status' => 'published',
            ],
            [
                'architect' => $sara,
                'title' => 'Le style boheme dans votre interieur',
                'content' => "Le style boheme mele les cultures, les epoques et les textures. Tapis superposes, coussins, plantes, rotin et textiles naturels creent une ambiance chaleureuse.",
                'status' => 'published',
            ],
            [
                'architect' => $sara,
                'title' => 'Mon projet Riad, coulisses d une renovation a Marrakech',
                'content' => "Ce projet a dure 8 mois. Le defi principal etait de preserver l'ame traditionnelle du riad tout en l'adaptant aux standards contemporains.",
                'status' => 'draft',
            ],
        ];

        foreach ($posts as $data) {
            if (!$data['architect']) {
                continue;
            }

            BlogPost::firstOrCreate(
                ['title' => $data['title']],
                [
                    'architect_id' => $data['architect']->id,
                    'title' => $data['title'],
                    'slug' => Str::slug($data['title']) . '-' . Str::random(5),
                    'content' => $data['content'],
                    'status' => $data['status'],
                ]
            );
        }
    }
}
