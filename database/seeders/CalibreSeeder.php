<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CalibreSeeder extends Seeder
{
    public function run(): void
    {
        // Désactiver les foreign keys le temps du truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('calibres')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Calibres pour les poissons uniquement
        $calibres = [
            [
                'type_produit' => 'poisson',
                'unite_vente'  => 'kg',
                'taille'       => 'grande',
                'poids_min'    => 1.5,
                'poids_max'    => null,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'type_produit' => 'poisson',
                'unite_vente'  => 'kg',
                'taille'       => 'moyen',
                'poids_min'    => 0.5,
                'poids_max'    => 1.5,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'type_produit' => 'poisson',
                'unite_vente'  => 'kg',
                'taille'       => 'petit',
                'poids_min'    => null,
                'poids_max'    => 0.5,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ];

        DB::table('calibres')->insert($calibres);

        $this->command->info('✅ Calibres poissons créés : Grande, Moyen, Petit (Silivie)');
    }
}