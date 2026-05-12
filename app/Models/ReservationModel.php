<?php namespace App\Models;
use CodeIgniter\Model;
class ReservationModel extends Model {
    protected $table = 'reservations';
    protected $allowedFields = ['user_id', 'creneau_id', 'statut'];
}