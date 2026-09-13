<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Auth;
use App\Core\Session;

class AuthController extends Controller
{
    public function loginForm(): void
    {
        $this->view('auth/login', ['title' => 'Login']);
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Csrf::verify($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid request.');
            $this->redirect('/login');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $auth = new Auth();
        if ($auth->attempt($email, $password)) {
            $this->redirect('/dashboard');
        } else {
            flash('error', 'Invalid credentials or account locked.');
            $this->redirect('/login');
        }
    }

    public function logout(): void
    {
        Session::destroy();
        $this->redirect('/login');
    }
}
