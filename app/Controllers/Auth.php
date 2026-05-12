<?php
namespace App\Controllers;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function doLogin()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $model = new UserModel();
        $user  = $model->where('email', $email)->first();

        if (!$user) {
            return redirect()->to('/register')
                             ->with('info', 'Aucun compte trouvé. Créez-en un ici !');
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->back()
                             ->with('error', 'Mot de passe incorrect.');
        }

        session()->set([
            'isLoggedIn' => true,
            'user_id'    => $user['id'],
            'user_name'  => $user['name'],
        ]);

        return redirect()->to('/home');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function doRegister()
    {
        helper(['form']);

        $model = new UserModel();

        $password        = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Vérifier mot de passe
        if ($password !== $confirmPassword) {
            return redirect()->back()->withInput()
                             ->with('error', 'Les mots de passe ne correspondent pas.');
        }

        // Vérifier email existant
        $existingUser = $model->where('email', $this->request->getPost('email'))->first();
        if ($existingUser) {
            return redirect()->back()->withInput()
                             ->with('error', 'Cet email est déjà utilisé.');
        }

        $data = [
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ];

        // 🔴 DEBUG IMPORTANT
        if (!$model->insert($data)) {
            dd($model->errors()); // 👈 ça va afficher l’erreur réelle
        }

        return redirect()->to('/login')
                         ->with('success', 'Compte créé ! Vous pouvez vous connecter.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}