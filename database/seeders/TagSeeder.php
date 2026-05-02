<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'Moderne',
            'Minimaliste',
            'Scandinave',
            'Industriel',
            'Bohème',
            'Classique',
            'Contemporain',
            'Rustique',
            'Art Déco',
            'Japonisant',
        ];

        foreach ($tags as $name) {
            Tag::firstOrCreate(['name' => $name]);
        }
    }
}