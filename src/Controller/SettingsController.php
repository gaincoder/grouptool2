<?php

namespace App\Controller;

use App\Form\SettingsFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SettingsController extends AbstractController
{
    /**
     * @Route("/settings", name="settings")
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(SettingsFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user->telegramUsername = str_replace('@', '', $user->telegramUsername);
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Einstellungen wurden gespeichert!');
        }
        return $this->render('closed_area/settings.html.twig', ['form' => $form->createView()]);
    }


}
