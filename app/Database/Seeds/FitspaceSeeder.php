<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FitspaceSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // Désactivation temporaire des clés étrangères pour SQLite
        $db->simpleQuery('PRAGMA foreign_keys = OFF;');

        try {
            // Nettoyage des tables
            $db->table('reservations')->truncate();
            $db->table('creneaux')->truncate();

            echo "Tables vidées avec succès.\n";

            // ==================== RESSOURCES ====================
            $ressourceModel = new \App\Models\RessourceModel();

            // Vérifier s'il y a déjà des ressources
            if ($ressourceModel->countAll() === 0) {
                $ressources = [
                    [
                        'nom'         => 'Salle Zen',
                        'type'        => 'cours',
                        'capacite'    => 10,
                        'description' => 'Espace dédié au yoga et à la relaxation.'
                    ],
                    [
                        'nom'         => 'Plateau Musculation',
                        'type'        => 'salle',
                        'capacite'    => 25,
                        'description' => 'Accès libre aux équipements de cardio et musculation.'
                    ],
                    [
                        'nom'         => 'Court de Squash',
                        'type'        => 'terrain',
                        'capacite'    => 2,
                        'description' => 'Terrain de squash pour deux personnes maximum.'
                    ]
                ];

                foreach ($ressources as $r) {
                    $ressourceModel->insert($r);
                }
                echo "✅ " . count($ressources) . " ressources créées.\n";
            } else {
                echo "ℹ️ Ressources déjà existantes.\n";
            }

            // ==================== CRÉNEAUX ====================
            $creneauModel = new \App\Models\CreneauxModel();
            $ressources = $db->table('ressources')->get()->getResultArray();

            $creneauxCrees = 0;

            foreach ($ressources as $res) {
                // Créneau Matin
                $creneauModel->insert([
                    'ressource_id' => $res['id'],
                    'date_debut'   => date('Y-m-d', strtotime('+1 day')) . ' 08:00:00',
                    'date_fin'     => date('Y-m-d', strtotime('+1 day')) . ' 09:30:00',
                    'places_dispo' => $res['capacite'],
                    'actif'        => 1,
                ]);
                $creneauxCrees++;

                // Créneau Après-midi
                $creneauModel->insert([
                    'ressource_id' => $res['id'],
                    'date_debut'   => date('Y-m-d', strtotime('+1 day')) . ' 15:00:00',
                    'date_fin'     => date('Y-m-d', strtotime('+1 day')) . ' 16:30:00',
                    'places_dispo' => $res['capacite'],
                    'actif'        => 1,
                ]);
                $creneauxCrees++;
            }

            echo "✅ $creneauxCrees créneaux créés avec succès.\n";

        } catch (\Exception $e) {
            echo "❌ Erreur : " . $e->getMessage() . "\n";
        }

        // Réactivation des clés étrangères
        $db->simpleQuery('PRAGMA foreign_keys = ON;');
    }
}