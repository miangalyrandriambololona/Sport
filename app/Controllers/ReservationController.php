<?php
namespace App\Controllers;

use App\Models\CreneauxModel;
use App\Models\ReservationModel;

class ReservationController extends BaseController {

    public function index() {
        $model = new CreneauxModel();
        // Récupère les créneaux actifs
        $data['creneaux'] = $model->where('actif', 1)->findAll();
        return view('creneaux_view', $data);
    }

    public function reserver($id) {
        if (!session()->get('isLoggedIn')) return redirect()->to('/login');

        $resModel = new ReservationModel();
        $creneauModel = new CreneauxModel();
        $creneau = $creneauModel->find($id);

        if ($creneau && $creneau['places_dispo'] > 0) {
            // Création de la réservation
            $resModel->insert([
                'user_id' => session()->get('user_id'),
                'creneau_id' => $id,
                'statut' => 'en attente'
            ]);

            // Mise à jour des places
            $creneauModel->update($id, ['places_dispo' => $creneau['places_dispo'] - 1]);

            return redirect()->to('/creneaux')->with('success', 'Réservation effectuée !');
        }
        return redirect()->to('/creneaux')->with('error', 'Plus de places disponibles.');
    }
}