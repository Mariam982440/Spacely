<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ArchitectProfile;
use App\Models\Project;
use App\Models\Tag;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $karim   = ArchitectProfile::whereHas('user', fn($q) => $q->where('email', 'karim@spacely.com'))->first();
        $sara    = ArchitectProfile::whereHas('user', fn($q) => $q->where('email', 'sara@spacely.com'))->first();
        $youssef = ArchitectProfile::whereHas('user', fn($q) => $q->where('email', 'youssef@spacely.com'))->first();

        $projects = [

            // ── Projets de Karim ──
            [
                'architect' => $karim,
                'title'     => 'Appartement Minimaliste — Casablanca',
                'description' => 'Rénovation complète d\'un appartement de 120m² dans le quartier Maarif. Palette de couleurs neutres, mobilier sur mesure, lumière naturelle optimisée.',
                'tags'      => ['Minimaliste', 'Contemporain', 'Moderne'],
            ],
            [
                'architect' => $karim,
                'title'     => 'Bureau Contemporain — Anfa',
                'description' => 'Aménagement d\'un espace de travail de 200m² pour une startup tech. Open space modulable, salle de réunion vitrée, espace détente intégré.',
                'tags'      => ['Contemporain', 'Moderne'],
            ],
            [
                'architect' => $karim,
                'title'     => 'Penthouse Art Déco — Tour Casablanca',
                'description' => 'Projet de luxe avec vue panoramique sur l\'océan. Moulures dorées, marbre de Carrare, velours émeraude.',
                'tags'      => ['Art Déco', 'Classique'],
            ],

            // ── Projets de Sara ──
            [
                'architect' => $sara,
                'title'     => 'Riad Bohème — Médina Marrakech',
                'description' => 'Transformation d\'un riad traditionnel en hébergement boutique. Tissus berbères, plantes tropicales, fontaine centrale en zellige.',
                'tags'      => ['Bohème', 'Rustique'],
            ],
            [
                'architect' => $sara,
                'title'     => 'Villa Scandinave — Guéliz',
                'description' => 'Maison familiale de 300m² dans un style nordique épuré. Bois clair, textiles doux, plantes vertes, lumières chaleureuses.',
                'tags'      => ['Scandinave', 'Minimaliste'],
            ],

            // ── Projets de Youssef ──
            [
                'architect' => $youssef,
                'title'     => 'Loft Industriel — Agdal Rabat',
                'description' => 'Conversion d\'un ancien entrepôt en loft résidentiel. Béton brut, acier noir, brique apparente, hauteur sous plafond de 5 mètres.',
                'tags'      => ['Industriel', 'Contemporain'],
            ],
        ];

        foreach ($projects as $data) {
            $existing = Project::where('title', $data['title'])
                ->where('architect_id', $data['architect']->id)
                ->first();

            if ($existing) continue;

            $project = Project::create([
                'architect_id' => $data['architect']->id,
                'title'        => $data['title'],
                'description'  => $data['description'],
            ]);

            // Attacher les tags
            $tagIds = Tag::whereIn('name', $data['tags'])->pluck('id');
            $project->tags()->attach($tagIds);

            // Créer une image placeholder (pas de vrai fichier en seed)
            $project->images()->create([
                'image_path' => 'projects/placeholder.jpg',
                'is_before'  => false,
                'is_after'   => false,
            ]);
        }
    }
}