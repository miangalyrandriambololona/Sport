<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FitspaceSeeder extends Seeder
{
    public function run()
    {
        // 1. Création de l'Administrateur par défaut
        $adminData = [
            'nom'      => 'Admin FitSpace',
            'email'    => 'admin@fitspace.mg',
            // password_hash permet de sécuriser le mot de passe "admin123"
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'role'     => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        // On insère l'admin dans la table 'users'
        $this->db->table('users')->insert($adminData);

        // 2. Création d'une ressource de test (Une salle)
        $ressourceData = [
            'nom'         => 'Salle Zen',
            'type'        => 'cours',
            'capacite'    => 10,
            'description' => 'Espace dédié au yoga et à la relaxation.',
        ];

        $this->db->table('ressources')->insert($ressourceData);
        
        // On récupère l'ID de la ressource qu'on vient de créer pour le créneau
        $ressourceId = $this->db->insertID();

        // 3. Création d'un créneau de test (Demain à 08h00)
        $creneauData = [
            'ressource_id' => $ressourceId,
            'date_debut'   => date('Y-m-d', strtotime('+1 day')) . ' 08:00:00',
            'date_fin'     => date('Y-m-d', strtotime('+1 day')) . ' 09:30:00',
            'places_dispo' => 10,
            'actif'        => 1,
        ];

        $this->db->table('creneaux')->insert($creneauData);
    }
}