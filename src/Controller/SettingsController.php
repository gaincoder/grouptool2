<?php

namespace App\Controller;

use App\Form\PublicGroupFormType;
use App\Form\SettingsFormType;
use App\Manager\GroupManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Templating\EngineInterface;

class SettingsController
{

    /**
     * @var EngineInterface
     */
    private $twig;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var UserManagerInterface
     */
    private $manager;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(EngineInterface $twig, FormFactoryInterface $formFactory, RouterInterface $router, Security $security, UserManagerInterface $manager,FlashBagInterface $flashBag )
    {


        $this->twig = $twig;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->manager = $manager;
        $this->security = $security;
        $this->flashBag = $flashBag;
    }

    /**
     * @Route("/settings", name="settings")
     */
    public function indexAction(Request $request)
    {
        $user = $this->security->getUser();
        $form = $this->formFactory->create(SettingsFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->updateUser($user);
            $this->flashBag->add('success', 'Einstellungen wurden gespeichert!');
        }
        return new Response($this->twig->render('closed_area/settings.html.twig', ['form' => $form->createView()]));
    }

    /**
     * @Route("/publicGroups", name="public_groups")
     */
    public function publicGroupsAction(Request $request)
    {
        $user = $this->security->getUser();
        $form = $this->formFactory->create(PublicGroupFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->updateUser($user);
            $this->flashBag->add('success', 'Einstellungen wurden gespeichert!');
        }
        return new Response($this->twig->render('closed_area/publicgroups.html.twig', ['form' => $form->createView()]));
    }


}
