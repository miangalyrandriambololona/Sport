<?php

namespace App\Models;

use CodeIgniter\Model;

class CreneauxModel extends Model
{
    // Nom de la table dans votre base SQLite
    protected $table      = 'creneaux';
    protected $primaryKey = 'id';

    // Autoriser CodeIgniter à modifier ces colonnes (important pour décrémenter les places)
    protected $allowedFields = [
        'ressource_id', 
        'date_debut', 
        'date_fin', 
        'places_dispo', 
        'actif'
    ];

    // Optionnel : définit le type de retour en tableau
    protected $returnType = 'array';
}