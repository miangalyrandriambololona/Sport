<?php

namespace App\Controllers;

use App\Models\MealModel;
use App\Models\SwipeModel;

class Swipe extends BaseController
{
    // Enregistre un swipe (like/super/skip)
    public function doSwipe()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'Non connecté'])->setStatusCode(401);
        }
        
        $mealId = $this->request->getPost('meal_id');
        $action = $this->request->getPost('action');
        $userId = session()->get('user_id');
        
        if (!in_array($action, ['like', 'super', 'skip'])) {
            return $this->response->setJSON(['error' => 'Action invalide'])->setStatusCode(400);
        }
        
        $swipeModel = new SwipeModel();
        
        // Vérifier si déjà swipé
        $existing = $swipeModel->where('user_id', $userId)
                               ->where('meal_id', $mealId)
                               ->first();
        if ($existing) {
            return $this->response->setJSON(['error' => 'Déjà swipé'])->setStatusCode(400);
        }
        
        // Enregistrer
        $swipeModel->insert([
            'user_id' => $userId,
            'meal_id' => $mealId,
            'action' => $action
        ]);
        
        return $this->response->setJSON(['success' => true]);
    }
    
    // Récupère le prochain plat non swipé
    public function nextMeal()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'Non connecté'])->setStatusCode(401);
        }
        
        $mealModel = new MealModel();
        $unswiped = $mealModel->getUnswipedMeals(session()->get('user_id'));
        
        if (empty($unswiped)) {
            return $this->response->setJSON(['empty' => true]);
        }
        
        $meal = $unswiped[0];
        
        // Ajouter l'URL complète de l'image
        if ($meal['image'] && !str_starts_with($meal['image'], 'http')) {
            $meal['image_url'] = base_url($meal['image']);
        } else {
            $meal['image_url'] = null;
        }
        
        return $this->response->setJSON(['meal' => $meal]);
    }
}