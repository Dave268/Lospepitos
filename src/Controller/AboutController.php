<?php
// src/Controller/AboutController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AboutController extends AbstractController
{
    public function about()
    {
        return $this->render('pages/about/about.html.twig');
    }
}