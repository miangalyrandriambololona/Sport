<?php

namespace App\Controllers;

use App\Models\MealModel;
use App\Models\SwipeModel;

class Stats extends BaseController
{
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        
        $userId = session()->get('user_id');
        $swipeModel = new SwipeModel();
        $mealModel = new MealModel();
        
        $stats = $swipeModel->getStats($userId);
        $categoryStats = $swipeModel->getCategoryStats($userId);
        $likedMeals = $mealModel->getLikedMeals($userId);
        
        $totalMeals = $mealModel->countAll();
        $likeRate = $stats['seen'] > 0 ? round(($stats['liked'] / $stats['seen']) * 100) : 0;
        
        return view('stats', [
            'likedCount' => $stats['liked'],
            'superCount' => $stats['super'],
            'seenCount' => $stats['seen'],
            'categoryStats' => $categoryStats,
            'likedMeals' => $likedMeals,
            'likeRate' => $likeRate,
            'totalMeals' => $totalMeals
        ]);
    }
}