<?php

namespace App\Controllers;

use App\Models\CreneauxModel; // Assurez-vous que le nom correspond à votre modèle
use App\Models\ReservationModel;

class CreneauxController extends BaseController
{
    public function index()
    {
        $model = new CreneauxModel();
        
        // On récupère les créneaux avec les infos de la ressource (jointure)
        // et on ne prend que ceux qui sont actifs
        $data['creneaux'] = $model->select('creneaux.*, ressources.nom as ressource_nom, ressources.type')
                                  ->join('ressources', 'ressources.id = creneaux.ressource_id')
                                  ->where('creneaux.actif', 1)
                                  ->findAll();

        return view('liste_creneaux', $data);
    }

    public function reserver($id)
    {
        $session = session();
        $userId = $session->get('user_id'); // On suppose que l'utilisateur est connecté

        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Veuillez vous connecter.');
        }

        $creneauModel = new CreneauxModel();
        $resModel = new ReservationModel();

        $creneau = $creneauModel->find($id);

        // Vérification s'il reste des places
        if ($creneau['places_dispo'] > 0) {
            // 1. Créer la réservation
            $resModel->insert([
                'user_id' => $userId,
                'creneau_id' => $id,
                'statut' => 'en attente'
            ]);

            // 2. Décrémenter les places disponibles
            $creneauModel->update($id, [
                'places_dispo' => $creneau['places_dispo'] - 1
            ]);

            return redirect()->back()->with('success', 'Réservation effectuée !');
        }

        return redirect()->back()->with('error', 'Plus de places disponibles.');
    }
}