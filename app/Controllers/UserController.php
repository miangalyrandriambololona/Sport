<?php namespace App\Controllers;
use App\Models\UserModel;

class UserController extends BaseController {

    public function register() { return view('register_view'); }

    public function register_action() {
        $model = new UserModel();
        $model->save([
            'nom'      => $this->request->getVar('nom'),
            'email'    => $this->request->getVar('email'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'role'     => 'client'
        ]);
        return redirect()->to('/login')->with('success', 'Inscription réussie !');
    }

    public function login() { return view('login_view'); }

    public function login_action() {
        $session = session();
        $model = new UserModel();
        $user = $model->where('email', $this->request->getVar('email'))->first();

        if($user && password_verify($this->request->getVar('password'), $user['password'])) {
            $session->set(['user_id' => $user['id'], 'user_nom' => $user['nom'], 'isLoggedIn' => true]);
            return redirect()->to('/creneaux');
        }
        return redirect()->back()->with('error', 'Identifiants incorrects');
    }

    public function logout() { session()->destroy(); return redirect()->to('/login'); }
}