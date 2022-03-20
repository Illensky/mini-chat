<?php

use App\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * Home page
     * @return void
     */
    public static function index()
    {
        self::render('home/home');
    }
}