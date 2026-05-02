<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,       // 1. Rôles en premier (users en dépend)
            TagSeeder::class,        // 2. Tags (projects en dépend)
            UserSeeder::class,       // 3. Users + profils
            ProjectSeeder::class,    // 4. Projets + images + tags
            AvailabilitySeeder::class, // 5. Disponibilités + créneaux
            BookingSeeder::class,    // 6. Réservations + devis
            MessageSeeder::class,    // 7. Messages
            BlogSeeder::class,       // 8. Articles blog
        ]);

        $this->command->info('');
        $this->command->info('Base de données peuplée avec succès !');
        $this->command->info('');
        $this->command->info('Comptes de test (mot de passe : password)');
        $this->command->info('─────────────────────────────────────────');
        $this->command->info('  Admin      : admin@spacely.com');
        $this->command->info('  Architecte : karim@spacely.com');
        $this->command->info('  Architecte : sara@spacely.com');
        $this->command->info('  Architecte : youssef@spacely.com  (non vérifié)');
        $this->command->info('  Client     : nadia@spacely.com');
        $this->command->info('  Client     : omar@spacely.com');
        $this->command->info('  Client     : leila@spacely.com');
        $this->command->info('');
    }
}