<?php

namespace App\Controller;

use App\Entity\Poll;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PublicAreaController extends AbstractController
{

    /**
     * @Route("/impressum", name="impressum")
     */
    public function impressumAction()
    {
        return $this->render('public_area/impressum.html.twig');
    }

    /**
     * @Route("/datenschutz", name="datenschutz")
     */
    public function datenschutzAction()
    {
        return $this->render('public_area/datenschutz.html.twig');
    }

    /**
     * @Route("/ausbildung", name="ausbildung")
     */
    public function ausbildungAction()
    {
        return $this->render('public_area/ausbildung.html.twig');
    }



}
