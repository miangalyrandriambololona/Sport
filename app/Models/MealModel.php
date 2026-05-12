<?php

namespace App\Models;

use CodeIgniter\Model;

class MealModel extends Model
{
    protected $table = 'meals';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id', 'name', 'emoji', 'image',
        'category', 'time_min', 'calories',
        'rating', 'description', 'is_default'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;
    
    // Récupère les plats non encore swipés par l'utilisateur
    public function getUnswipedMeals($userId)
    {
        $db = \Config\Database::connect();
        return $db->table('meals')
            ->select('meals.*')
            ->join('swipes', 'swipes.meal_id = meals.id AND swipes.user_id = ' . $userId, 'left')
            ->where('swipes.id IS NULL', null, false)
            ->orderBy('meals.id', 'ASC')
            ->get()
            ->getResultArray();
    }
    
    // Récupère les plats likés par l'utilisateur
    public function getLikedMeals($userId)
    {
        $db = \Config\Database::connect();
        return $db->table('swipes')
            ->select('meals.*, swipes.action')
            ->join('meals', 'meals.id = swipes.meal_id')
            ->where('swipes.user_id', $userId)
            ->whereIn('swipes.action', ['like', 'super'])
            ->orderBy('swipes.swiped_at', 'DESC')
            ->get()
            ->getResultArray();
    }
}