<?php

namespace App\controllers\home;

class HomeController
{
    public function index(): void
    {
        include 'app/views/index.php';
    }
}