<?php 
namespace App\Controllers;

use App\Models\CreneauxModel;
use App\Models\ReservationModel;

class ReservationController extends BaseController {

    public function index() {
        $model = new CreneauxModel();
        // Jointure pour afficher le NOM de la salle au lieu de l'ID
        $data['creneaux'] = $model->select('creneaux.*, ressources.nom as ressource_nom')
                                  ->join('ressources', 'ressources.id = creneaux.ressource_id')
                                  ->where('creneaux.actif', 1)
                                  ->findAll();

        return view('creneaux_view', $data);
    }

    public function reserver($id) {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Connectez-vous pour réserver.');
        }

        $resModel = new ReservationModel();
        $creModel = new CreneauxModel();
        $creneau  = $creModel->find($id);

        if ($creneau && $creneau['places_dispo'] > 0) {
            // Insérer la réservation
            $resModel->insert([
                'user_id'    => session()->get('user_id'),
                'creneau_id' => $id,
                'statut'     => 'en attente'
            ]);

            // Mettre à jour le nombre de places (-1)
            $creModel->update($id, ['places_dispo' => $creneau['places_dispo'] - 1]);
            
            return redirect()->to('/creneaux')->with('success', 'Réservation confirmée !');
        }
        return redirect()->back()->with('error', 'Ce créneau est complet.');
    }
}