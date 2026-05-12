<?php

namespace App\Controllers;

use App\Models\MealModel;

class Food extends BaseController
{
    public function addForm()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        return view('add_food');
    }
    
    public function save()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'category' => 'required',
            'time_min' => 'required|numeric|greater_than[0]',
            'calories' => 'required|numeric|greater_than[0]',
            'rating' => 'required|numeric|greater_than_equal_to[1]|less_than_equal_to[5]',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $mealModel = new MealModel();
        
        // Gestion de l'image
        $imagePath = null;
        $file = $this->request->getFile('image');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if ($file->getSize() <= 5 * 1024 * 1024) {
                // Créer le dossier s'il n'existe pas
                $uploadPath = FCPATH . 'uploads/meals/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                $newName = $file->getRandomName();
                $file->move($uploadPath, $newName);
                $imagePath = '/uploads/meals/' . $newName;
            }
        }
        
        $data = [
            'user_id' => session()->get('user_id'),
            'name' => $this->request->getPost('name'),
            'emoji' => $this->request->getPost('emoji') ?: '🍽️',
            'image' => $imagePath,
            'category' => $this->request->getPost('category'),
            'time_min' => $this->request->getPost('time_min'),
            'calories' => $this->request->getPost('calories'),
            'rating' => $this->request->getPost('rating'),
            'description' => $this->request->getPost('description') ?: '',
            'is_default' => 0
        ];
        
        if ($mealModel->insert($data)) {
            return redirect()->to('/add-food')->with('success', 'Plat ajouté avec succès ! 🎉');
        }
        
        return redirect()->back()->withInput()->with('error', 'Erreur lors de l\'ajout.');
    }
}