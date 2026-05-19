<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController {

    public function login() {
        return view('login_view');
    }

    public function login_action() {
        $model = new UserModel();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $user = $model->where('email', $email)->first();

        // Vérification sécurisée du mot de passe haché
        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'user_id'    => $user['id'],
                'user_nom'   => $user['nom'],
                'isLoggedIn' => true,
                'role'       => $user['role'] // Stocke 'client' ou 'admin'
            ]);

            // REDIRECTION AUTOMATIQUE SELON LE RÔLE
            if ($user['role'] === 'admin') {
                return redirect()->to('/admin/creneaux'); // Redirige vers le Back-office
            } else {
                return redirect()->to('/creneaux'); // Redirige vers l'espace Client
            }
        }
        
        return redirect()->back()->with('error', 'Identifiants invalides.');
    }

    public function register() {
        return view('register_view');
    }

    public function register_action() {
        $model = new UserModel();
        $email = $this->request->getVar('email');
        
        $data = [
            'nom'      => explode('@', $email)[0], 
            'email'    => $email,
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'role'     => 'client' // Rôle par défaut à l'inscription
        ];
        
        $model->save($data);
        return redirect()->to('/login')->with('success', 'Compte créé ! Connectez-vous.');
    }

    public function logout() {
        session()->destroy();
        return redirect()->to('/login');
    }
}