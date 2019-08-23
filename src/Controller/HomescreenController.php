<?php

namespace App\Controller;

use App\Entity\Poll;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomescreenController extends AbstractController
{
    /**
     * @Route("/home", name="homepage")
     */
    public function clodsedAreaAction()
    {
        return $this->render('closed_area/homescreen.html.twig');
    }

    /**
     * @Route("/", name="home")
     */
    public function publicAreaAction()
    {
        return $this->render('public_area/home.html.twig');
    }
}
