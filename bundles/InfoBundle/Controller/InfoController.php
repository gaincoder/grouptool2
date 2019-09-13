<?php

namespace InfoBundle\Controller;

use InfoBundle\Entity\Info;
use InfoBundle\Form\InfoFormType;
use InfoBundle\Manager\InfoManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

class InfoController
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
     * @var \InfoBundle\Manager\InfoManagerInterface
     */
    private $infoManager;

    public function __construct(EngineInterface $twig, FormFactoryInterface $formFactory, RouterInterface $router, InfoManagerInterface $infoManager)
    {

        $this->twig = $twig;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->infoManager = $infoManager;
    }

    /**
     * @Route("/info", name="info")
     */
    public function indexAction()
    {
        $infos = $this->infoManager->list();
        return new Response($this->twig->render('closed_area/Info/index.html.twig', ['infos' => $infos]));
    }

    /**
     * @Route("/info/create", name="info_create")
     * @IsGranted("ROLE_INFO_CREATE")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $info = $this->infoManager->createObject();
        $form = $this->formFactory->create(InfoFormType::class, $info);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->infoManager->handleCreate($info);

            return $this->redirectToRoute('info');
        }
        return new Response($this->twig->render('closed_area/Info/form.html.twig', ['form' => $form->createView(), 'page_title' => 'Info hinzufügen']));
    }


    /**
     * @Route("/info/edit/{info}", name="info_edit")
     * @IsGranted("ROLE_INFO_EDIT")
     * @param \InfoBundle\Entity\Info $info
     * @param Request $request
     * @return Response
     */
    public function edit(Info $info, Request $request)
    {
        $form = $this->formFactory->create(InfoFormType::class, $info);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->infoManager->handleEdit($info);

            return $this->redirectToRoute('info');
        }
        return new Response($this->twig->render('closed_area/Info/form.html.twig', ['form' => $form->createView(), 'page_title' => 'Info bearbeiten']));
    }

    /**
     * @Route("/info/delete/{info}/{confirm}", name="info_delete",defaults={"confirm"=false})
     * @IsGranted("ROLE_INFO_DELETE")
     * @param \InfoBundle\Entity\Info $info
     * @param bool $confirm
     * @return Response
     */
    public function delete(Info $info, $confirm = false)
    {
        if ($confirm == false) {
            return new Response($this->twig->render('closed_area/confirm.html.twig', ['type' => 'Info']));
        }
        $this->infoManager->handleDelete($info);

        return $this->redirectToRoute('info');

    }

    /**
     * @Route("/info/share/{info}", name="info_share")
     * @param info $info
     * @return RedirectResponse
     * @return RedirectResponse
     */
    public function share(info $info)
    {
        $this->infoManager->handleShare($info);
        return $this->redirectToRoute('info');
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
