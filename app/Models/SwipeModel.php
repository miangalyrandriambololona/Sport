<?php

namespace App\Models;

use CodeIgniter\Model;

class SwipeModel extends Model
{
    protected $table      = 'swipes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'meal_id', 'action'];
    protected $useTimestamps = true;
    protected $createdField  = 'swiped_at';
    protected $updatedField  = '';

    public function getStats($userId)
    {
        $liked = $this->where('user_id', $userId)->whereIn('action', ['like', 'super'])->countAllResults();
        $super = $this->where('user_id', $userId)->where('action', 'super')->countAllResults();
        $seen  = $this->where('user_id', $userId)->countAllResults();

        return ['liked' => $liked, 'super' => $super, 'seen' => $seen];
    }

    public function getCategoryStats($userId)
    {
        $db = \Config\Database::connect();
        return $db->table('swipes')
            ->select('meals.category, COUNT(*) as count')
            ->join('meals', 'meals.id = swipes.meal_id')
            ->where('swipes.user_id', $userId)
            ->whereIn('swipes.action', ['like', 'super'])
            ->groupBy('meals.category')
            ->orderBy('count', 'DESC')
            ->get()
            ->getResultArray();
    }
}