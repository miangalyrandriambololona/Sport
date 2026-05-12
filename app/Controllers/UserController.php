<?php
namespace App\Controllers;
use App\Models\UserModel;

class UserController extends BaseController {

    public function register() {
        return view('register_view'); // Créez ce fichier dans Views
    }

    public function login() {
        return view('login_view'); // Créez ce fichier dans Views
    }

    public function login_action() {
        $session = session();
        $model = new UserModel();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        
        $user = $model->where('email', $email)->first();

        if($user && password_verify($password, $user['password'])) {
            $session->set([
                'user_id' => $user['id'],
                'user_role' => $user['role'],
                'isLoggedIn' => true
            ]);
            return redirect()->to('/creneaux');
        }
        return redirect()->back()->with('error', 'Identifiants incorrects');
    }

    public function logout() {
        session()->destroy();
        return redirect()->to('/login');
    }
}