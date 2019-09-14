<?php

namespace App\Controller;

use App\Entity\Group;
use App\Enums\Roles;
use App\Form\GroupFormType;
use App\Manager\GroupManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

class GroupController
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
     * @var \GroupBundle\Manager\GroupManagerInterface
     */
    private $groupManager;


    public function __construct(EngineInterface $twig, FormFactoryInterface $formFactory, RouterInterface $router, GroupManagerInterface $groupManager )
    {

        $this->twig = $twig;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->groupManager = $groupManager;

    }

    /**
     * @Route("/group", name="group")
     * @IsGranted("ROLE_GROUP_VIEWLIST")
     */
    public function indexAction()
    {
        $groups = $this->groupManager->list();
        return new Response($this->twig->render('closed_area/Group/index.html.twig', ['groups' => $groups]));
    }

    /**
     * @Route("/group/create", name="group_create")
     * @IsGranted("ROLE_GROUP_CREATE")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $group = $this->groupManager->createObject();
        $form = $this->formFactory->create(GroupFormType::class, $group);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->groupManager->handleCreate($group);

            return $this->redirectToRoute('group');
        }
        return new Response($this->twig->render('closed_area/Group/form.html.twig', ['form' => $form->createView(), 'page_title' => 'Gruppe hinzufügen']));
    }


    /**
     * @Route("/group/edit/{group}", name="group_edit")
     * @IsGranted("ROLE_GROUP_EDIT")
     * @param \GroupBundle\Entity\Group $group
     * @param Request $request
     * @return Response
     */
    public function edit(Group $group, Request $request)
    {
        $list = Roles::getList();
        $form = $this->formFactory->create(GroupFormType::class, $group);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->groupManager->handleEdit($group);

            return $this->redirectToRoute('group');
        }
        return new Response($this->twig->render('closed_area/Group/form.html.twig', ['form' => $form->createView(), 'page_title' => 'Gruppe bearbeiten']));
    }

    /**
     * @Route("/group/delete/{group}/{confirm}", name="group_delete",defaults={"confirm"=false})
     * @IsGranted("ROLE_GROUP_DELETE")
     * @param \GroupBundle\Entity\Group $group
     * @param bool $confirm
     * @return Response
     */
    public function delete(Group $group, $confirm = false)
    {
        if ($confirm == false) {
            return new Response($this->twig->render('closed_area/confirm.html.twig', ['type' => 'Group']));
        }
        $this->groupManager->handleDelete($group);

        return $this->redirectToRoute('group');

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



}
