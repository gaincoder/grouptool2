<?php

namespace CompanyBundle\Controller;

use App\Entity\Group;
use CompanyBundle\Entity\Company;
use CompanyBundle\Form\CompanyFormType;
use CompanyBundle\Manager\CompanyManagerInterface;
use CompanyBundle\Repository\CompanyRepositoryInterface;
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

class CompanyController
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
     * @var \CompanyBundle\Manager\CompanyManagerInterface
     */
    private $companyManager;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var CompanyBundle\Repository\CompanyRepositoryInterface
     */
    private $repository;
    private $projectDir;

    public function __construct(EngineInterface $twig, FormFactoryInterface $formFactory, RouterInterface $router, CompanyManagerInterface $companyManager, EntityManagerInterface $entityManager, CompanyRepositoryInterface $repository, $projectDir)
    {

        $this->twig = $twig;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->companyManager = $companyManager;
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->projectDir = $projectDir;
    }

    /**
     * @Route("/company", name="company")
     * @IsGranted("ROLE_COMPANY_VIEWLIST")
     */
    public function indexAction()
    {
        $companies = $this->companyManager->list();
        return new Response($this->twig->render('closed_area/Company/index.html.twig', ['companies' => $companies]));
    }

    /**
     * @Route("/company/create", name="company_create")
     * @IsGranted("ROLE_COMPANY_CREATE")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $company = $this->companyManager->createObject();
        $form = $this->formFactory->create(CompanyFormType::class, $company);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $company->group = $this->createGroupForCompany($company);

            $logoFile = $form['logo']->getData();
            if ($logoFile) {
                $newFilename = uniqid().'.'.$logoFile->guessExtension();
                $logoFile->move(
                   $this->projectDir.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR,
                    $newFilename
                );
                $company->logoPath = $newFilename;
            }
            $this->companyManager->handleCreate($company);
            return $this->redirectToRoute('company');
        }
        return new Response($this->twig->render('closed_area/Company/form.html.twig', ['form' => $form->createView(), 'page_title' => 'Firma hinzufügen']));
    }


    /**
     * @Route("/company/edit/{company}", name="company_edit")
     * @IsGranted("ROLE_COMPANY_EDIT")
     * @param \CompanyBundle\Entity\Company $company
     * @param Request $request
     * @return Response
     */
    public function edit(Company $company, Request $request)
    {
        $form = $this->formFactory->create(CompanyFormType::class, $company);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $logoFile = $form['logo']->getData();
            if ($logoFile) {
                $newFilename = uniqid().'.'.$logoFile->guessExtension();
                $logoFile->move(
                    $this->projectDir.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR,
                    $newFilename
                );
                $company->logoPath = $newFilename;
            }
            $this->companyManager->handleEdit($company);

            return $this->redirectToRoute('company');
        }
        return new Response($this->twig->render('closed_area/Company/form.html.twig', ['form' => $form->createView(), 'page_title' => 'Firma bearbeiten']));
    }

    /**
     * @Route("/company/delete/{company}/{confirm}", name="company_delete",defaults={"confirm"=false})
     * @IsGranted("ROLE_COMPANY_DELETE")
     * @param \CompanyBundle\Entity\Company $company
     * @param bool $confirm
     * @return Response
     */
    public function delete(Company $company, $confirm = false)
    {
        if ($confirm == false) {
            return new Response($this->twig->render('closed_area/confirm.html.twig', ['type' => 'Company']));
        }
        $this->companyManager->handleDelete($company);

        return $this->redirectToRoute('company');

    }
    /**
     * @Route("/company/random", name="company_random")
     */
    public function showRandom()
    {
        $company = $this->repository->findOneRandom();
        return new Response($this->twig->render('public_area/company.html.twig', ['company' => $company[0]]));
    }

    /**
     * @Route("/companies", name="companies")
     */
    public function showAll()
    {
        $companies = $this->repository->findAllOrdered();
        return new Response($this->twig->render('public_area/companies.html.twig', ['companies' => $companies]));
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

    private function createGroupForCompany(Company $company): Group
    {
        $group = new Group($company->name);
        $this->entityManager->persist($group);
        return $group;
    }




}
