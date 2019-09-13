<?php

namespace App\Controller;

use App\Entity\ContactForm;
use App\Entity\Poll;
use App\Event\ContactFormSubmitted;
use App\Event\ContactFormSubmittedEvent;
use App\Form\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomescreenController extends Controller
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
    public function publicAreaAction(Request $request)
    {
        $data = new ContactForm();
        $form = $this->createForm(ContactFormType::class,$data,['timed_spam'=>true,'honeypot'=>true]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('event_dispatcher')->dispatch(new ContactFormSubmittedEvent($data));
            unset($data);
            unset($form);
            $data = new ContactForm();
            $form = $this->createForm(ContactFormType::class,$data);
        }
        return $this->render('public_area/home.html.twig',['form'=>$form->createView()]);
    }
}
