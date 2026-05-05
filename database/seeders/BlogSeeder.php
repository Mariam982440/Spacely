<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ArchitectProfile;
use App\Models\BlogPost;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $karim = ArchitectProfile::whereHas('user', fn($q) => $q->where('email', 'karim@spacely.com'))->first();
        $sara  = ArchitectProfile::whereHas('user', fn($q) => $q->where('email', 'sara@spacely.com'))->first();

        $posts = [
            [
                'architect' => $karim,
                'title'     => '5 tendances décoration intérieure à adopter en 2025',
                'content'   => "Le design intérieur évolue constamment, et 2025 ne fait pas exception. Voici les 5 grandes tendances qui domineront cette année.\n\n**1. Le minimalisme chaleureux**\nFini le minimalisme froid et stérile. La tendance actuelle mêle épurement des lignes avec des matières douces et des tons terreux : ocre, sable, taupe.\n\n**2. Les matières naturelles**\nBois, pierre, lin, rotin... Les matières naturelles envahissent les intérieurs pour créer une connexion avec la nature, même en ville.\n\n**3. Le coin lecture**\nAvec la résurgence de la lecture papier, un coin lecture bien pensé devient une pièce à part entière : fauteuil confortable, bibliothèque intégrée, lumière directionnelle.\n\n**4. Les arches**\nLes arches architecturales font leur retour dans les couloirs, les niches et les portes. Un détail qui apporte immédiatement du caractère.\n\n**5. Le vert en toutes nuances**\nDu vert sauge au vert émeraude, cette couleur s\'impose comme la teinte de l\'année pour les murs, les textiles et les plantes.",
                'status'    => 'published',
            ],
            [
                'architect' => $karim,
                'title'     => 'Comment optimiser la lumière naturelle dans votre appartement',
                'content'   => "La lumière naturelle est le premier matériau d\'un architecte d\'intérieur. Voici comment en tirer le meilleur parti.\n\n**Miroirs stratégiques**\nUn grand miroir placé face à une fenêtre peut doubler l\'impression de luminosité.\n\n**Couleurs réfléchissantes**\nLes blancs cassés, beiges et gris clairs réfléchissent la lumière.\n\n**Rideaux légers**\nÉvitez les rideaux épais en journée. Optez pour des voilages en lin ou en coton.\n\n**Plan ouvert**\nAbattre une cloison entre salon et cuisine peut transformer radicalement la luminosité.",
                'status'    => 'published',
            ],
            [
                'architect' => $sara,
                'title'     => 'Le style bohème : liberté et authenticité dans votre intérieur',
                'content'   => "Le style bohème, c\'est avant tout une philosophie de vie. C\'est l\'art de mêler les cultures, les époques et les textures sans règle stricte.\n\n**Les fondamentaux du boho**\nTapis superposés, coussins en abondance, plantes suspendues, objets rapportés de voyages.\n\n**La palette de couleurs**\nTerracotta, ocre, bordeaux, vert forêt.\n\n**Les matières clés**\nMacramé, rotin, velours, soie, coton naturel.\n\n**L\'erreur à éviter**\nLe boho ne signifie pas le désordre. Chaque objet doit avoir sa place et son histoire.",
                'status'    => 'published',
            ],
            [
                'architect' => $sara,
                'title'     => 'Mon projet Riad — Coulisses d\'une rénovation à Marrakech',
                'content'   => "Derrière chaque projet se cache des mois de travail, de recherche et de passion. Ce projet a duré 8 mois. Le défi principal était de préserver l\'âme traditionnelle du riad tout en l\'adaptant aux standards d\'un hébergement contemporain.\n\nNous avons fait appel à des artisans locaux pour les zelliges, les plâtres sculptés et les boiseries en cèdre.",
                'status'    => 'draft',
            ],
        ];

        foreach ($posts as $data) {
            BlogPost::firstOrCreate(
                ['title' => $data['title']],
                [
                    'architect_id' => $data['architect']->id,
                    'title'        => $data['title'],
                    'slug'         => Str::slug($data['title']) . '-' . Str::random(5),
                    'content'      => $data['content'],
                    'status'       => $data['status'],
                ]
            );
        }
    }
}