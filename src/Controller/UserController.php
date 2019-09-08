<?php

namespace App\Controller;

use App\Entity\User;

use App\Form\UserEditFormType;
use App\Manager\UserManagerInterface;
use App\Repository\UserRepositoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Templating\EngineInterface;

class UserController
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
    private $userManager;
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
    /**
     * @var Security
     */
    private $security;


    public function __construct(EngineInterface $twig, FormFactoryInterface $formFactory, RouterInterface $router, UserManagerInterface $userManager, UserRepositoryInterface $userRepository, Security $security)
    {

        $this->twig = $twig;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->userManager = $userManager;


        $this->userRepository = $userRepository;
        $this->security = $security;
    }

    /**
     * @Route("/userlist", name="userlist")
     */
    public function indexAction()
    {
        if (!($this->security->isGranted('ROLE_MANAGE_USER') || !$this->security->isGranted('ROLE_MANAGE_ALL_USER'))) {
            throw new AccessDeniedException();
        }
        $users = $this->userRepository->findAllOrdered($this->security->getUser()->company->id,$this->security->isGranted('ROLE_MANAGE_ALL_USER'));
        $notApprovedUsers = $this->userRepository->findNotApproved($this->security->getUser()->company->id,$this->security->isGranted('ROLE_MANAGE_ALL_USER'));
        return new Response($this->twig->render('closed_area/user/list.html.twig', ['users' => $users,'notApprovedUsers'=>$notApprovedUsers]));
    }


    /**
     * @Route("/user/edit/{user}", name="user_edit")
     * @param User
     * @param Request $request
     * @return Response
     */
    public function edit(User $user, Request $request)
    {
        $form = $this->formFactory->create(UserEditFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userManager->handleEdit($user);

            return $this->redirectToRoute('userlist');
        }
        return new Response($this->twig->render('closed_area/user/form.html.twig', ['form' => $form->createView(), 'page_title' => 'Benutzer bearbeiten']));
    }


    protected function redirectToRoute(string $route, array $parameters = [], int $status = 302): RedirectResponse
    {
        return $this->redirect($this->generateUrl($route, $parameters), $status);
    }

    protected function generateUrl(string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        return $this->router->generate($route, $parameters, $referenceType);
    }

    protected function redirect(string $url, int $status = 302): RedirectResponse
    {
        return new RedirectResponse($url, $status);
    }

    /**
     * @Route("/registration/isConfirmed", name="registration_isconfirmed")
     */
    public function registrationConfirmed()
    {
        return new Response($this->twig->render('public_area/register/confirmed.html.twig'));
    }

    /**
     * @Route("/user/approval/{user}", name="user_approval")
     * @param User
     * @param Request $request
     * @return Response
     */
    public function approval(User $user, Request $request)
    {
        return new Response($this->twig->render('closed_area/user/approval.html.twig', ['user' => $user, 'page_title' => 'Benutzer freischalten']));
    }

    /**
     * @Route("/user/approve/{user}", name="user_approved")
     * @param User
     * @param Request $request
     * @return Response
     */
    public function approve(User $user, Request $request)
    {
        $this->userManager->handleApproved($user);
        return $this->redirectToRoute('userlist');
    }

    /**
     * @Route("/user/refuse/{user}", name="user_refused")
     * @param User
     * @param Request $request
     * @return Response
     */
    public function refuse(User $user, Request $request)
    {
        $this->userManager->handleRefusal($user);
        return $this->redirectToRoute('userlist');
    }

}
