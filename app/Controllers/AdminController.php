<?php

namespace App\Controllers;

use App\Models\CreneauxModel;
use App\Models\ReservationModel;
use App\Models\RessourceModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class AdminController extends BaseController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);
        
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            header('Location: ' . site_url('creneaux'));
            exit;
        }
    }

    // Liste des créneaux
    public function index() {
        $creModel = new CreneauxModel();
        $data['creneaux'] = $creModel->select('creneaux.*, ressources.nom as ressource_nom')
                                    ->join('ressources', 'ressources.id = creneaux.ressource_id')
                                    ->findAll();
        return view('admin/creneaux_list', $data);
    }

    public function creer() {
        $resModel = new RessourceModel();
        $data['ressources'] = $resModel->findAll();
        return view('admin/creneau_creer', $data);
    }

    public function enregistrer() {
        $creModel = new CreneauxModel();
        $resModel = new RessourceModel();

        $ressourceId = $this->request->getVar('ressource_id');
        $ressource = $resModel->find($ressourceId);

        if ($ressource) {
            $creModel->insert([
                'ressource_id'  => $ressourceId,
                'date_debut'    => $this->request->getVar('date_debut'),
                'date_fin'      => $this->request->getVar('date_fin'),
                'places_dispo'  => $ressource['capacite'],
                'actif'         => 1
            ]);
            return redirect()->to('admin/creneaux')->with('success', 'Créneau créé avec succès.');
        }
        return redirect()->back()->with('error', 'Ressource introuvable.');
    }

    public function supprimer($id) {
        $creModel = new CreneauxModel();
        $creModel->delete($id);
        return redirect()->to('admin/creneaux')->with('success', 'Créneau supprimé.');
    }

    // Voir toutes les réservations
    public function reservations() {
        $resModel = new ReservationModel();
        
        $data['reservations'] = $resModel->select('reservations.*, users.email as user_email, creneaux.date_debut, ressources.nom as ressource_nom')
                                         ->join('users', 'users.id = reservations.user_id')
                                         ->join('creneaux', 'creneaux.id = reservations.creneau_id')
                                         ->join('ressources', 'ressources.id = creneaux.ressource_id')
                                         ->orderBy('reservations.id', 'DESC')
                                         ->findAll();
                                         
        return view('admin/reservations_list', $data);
    }

    /**
     * CHANGER LE STATUT
     */
    public function changer_statut($id, $nouveauStatut) {
        $resModel = new ReservationModel();
        $creModel = new CreneauxModel();

        $reservation = $resModel->find($id);

        if (!$reservation) {
            return redirect()->back()->with('error', 'Réservation introuvable.');
        }

        // Normalisation des statuts (supprime accents)
        $nouveauStatut = trim(strtolower($nouveauStatut));
        $nouveauStatut = str_replace(['é', 'è', 'ê', 'É'], 'e', $nouveauStatut);

        $ancienStatut = trim(strtolower($reservation['statut']));
        $ancienStatut = str_replace(['é', 'è', 'ê', 'É'], 'e', $ancienStatut);

        if ($ancienStatut === $nouveauStatut) {
            return redirect()->to('admin/reservations');
        }

        $creneau = $creModel->find($reservation['creneau_id']);

        if ($creneau) {
            // Confirmation
            if ($nouveauStatut === 'confirmee' && $ancienStatut !== 'confirmee') {
                if ($creneau['places_dispo'] <= 0) {
                    return redirect()->back()->with('error', 'Plus de places disponibles.');
                }
                if (in_array($ancienStatut, ['refusee', 'annulee'])) {
                    $creModel->update($reservation['creneau_id'], [
                        'places_dispo' => $creneau['places_dispo'] - 1
                    ]);
                }
            }

            // Refus ou annulation (libère une place)
            if (in_array($nouveauStatut, ['refusee', 'annulee']) && $ancienStatut === 'confirmee') {
                $creModel->update($reservation['creneau_id'], [
                    'places_dispo' => $creneau['places_dispo'] + 1
                ]);
            }

            // Refus d'une réservation en attente
            if ($nouveauStatut === 'refusee' && $ancienStatut === 'en attente') {
                $creModel->update($reservation['creneau_id'], [
                    'places_dispo' => $creneau['places_dispo'] + 1
                ]);
            }
        }

        $resModel->update($id, ['statut' => $nouveauStatut]);

        return redirect()->to('admin/reservations')->with('success', 'Statut mis à jour avec succès.');
    }
}