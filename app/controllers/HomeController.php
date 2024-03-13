<?php
namespace App\controllers;

use App\core\Controller\Controller;

class HomeController extends Controller {
    public function index() {
        $this->view->render('home' . DIRECTORY_SEPARATOR . 'home.php', 'template.php');
    }
}