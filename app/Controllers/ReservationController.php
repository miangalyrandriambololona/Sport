<?php 
namespace App\Controllers;

use App\Models\CreneauxModel;
use App\Models\ReservationModel;

class ReservationController extends BaseController {

    // 1. Consulter les créneaux disponibles [cite: 3, 17]
    public function index() {
        $model = new CreneauxModel();
        
        $data['creneaux'] = $model->select('creneaux.*, ressources.nom as ressource_nom')
                                  ->join('ressources', 'ressources.id = creneaux.ressource_id')
                                  ->where('creneaux.actif', 1)
                                  ->findAll();

        return view('creneaux_view', $data);
    }

    // 2. Réserver un créneau (1 place = 1 réservation) [cite: 18]
    public function reserver($id) {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Connectez-vous pour réserver.');
        }

        $resModel = new ReservationModel();
        $creModel = new CreneauxModel();
        $creneau  = $creModel->find($id);

        if ($creneau && $creneau['places_dispo'] > 0) {
            $resModel->insert([
                'user_id'    => session()->get('user_id'),
                'creneau_id' => $id,
                'statut'     => 'en attente'
            ]);

            $creModel->update($id, ['places_dispo' => $creneau['places_dispo'] - 1]);
            
            return redirect()->to('/mes-reservations')->with('success', 'Réservation effectuée ! En attente de validation.');
        }
        return redirect()->back()->with('error', 'Ce créneau est complet.');
    }

    // 3. Voir ses réservations (Tableau de bord) [cite: 18]
    public function mes_reservations() {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $resModel = new ReservationModel();
        
        $data['reservations'] = $resModel->select('reservations.*, creneaux.date_debut, ressources.nom as ressource_nom')
            ->join('creneaux', 'creneaux.id = reservations.creneau_id')
            ->join('ressources', 'ressources.id = creneaux.ressource_id')
            ->where('reservations.user_id', session()->get('user_id'))
            ->findAll();

        return view('mes_reservations_view', $data);
    }

    // 4. Annuler une réservation en attente [cite: 19]
    public function annuler($id) {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $resModel = new ReservationModel();
        $creModel = new CreneauxModel();

        $reservation = $resModel->where('id', $id)->where('user_id', session()->get('user_id'))->first();

        if ($reservation && $reservation['statut'] === 'en attente') {
            $resModel->update($id, ['statut' => 'annulee']);

            $creneau = $creModel->find($reservation['creneau_id']);
            if ($creneau) {
                $creModel->update($reservation['creneau_id'], [
                    'places_dispo' => $creneau['places_dispo'] + 1
                ]);
            }

            return redirect()->to('/mes-reservations')->with('success', 'Réservation annulée.');
        }

        return redirect()->back()->with('error', 'Impossible d\'annuler cette réservation.');
    }
}