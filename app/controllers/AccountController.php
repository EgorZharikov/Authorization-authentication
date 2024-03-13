<?php

namespace App\controllers;

use App\core\Controller\Controller;
use App\core\Auth\Auth;

class AccountController extends Controller
{
    public function index()
    {
        Auth::signin();
        $this->view->render('account' . DIRECTORY_SEPARATOR . 'signin.php', 'template.php');
    }

    public function signup()
    {
        Auth::signup();
        $this->view->render('account' . DIRECTORY_SEPARATOR . 'signup.php', 'template.php');
    }

    public function signin()
    {
        Auth::signin();
        $this->view->render('account' . DIRECTORY_SEPARATOR . 'signin.php', 'template.php');
    }

    public function profile()
    {
        Auth::check_auth();
        $this->view->render('account' . DIRECTORY_SEPARATOR . 'profile.php', 'template.php');
    }

    public function signout()
    {

        Auth::signout();
    }

    public function oauth()
    {
        Auth::getAccessToken();
    }
}
