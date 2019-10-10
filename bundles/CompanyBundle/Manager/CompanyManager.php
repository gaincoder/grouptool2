<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 03.07.2019
 * Time: 23:29
 */

namespace CompanyBundle\Manager;


use Doctrine\ORM\EntityManagerInterface;
use CompanyBundle\Entity\Company;
use CompanyBundle\Event\CompanyCreatedEvent;
use CompanyBundle\Event\CompanyDeletedEvent;
use CompanyBundle\Event\CompanyEditedEvent;
use CompanyBundle\Event\CompanySharedEvent;
use CompanyBundle\Repository\CompanyRepositoryInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class CompanyManager implements CompanyManagerInterface
{

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var CompanyRepositoryInterface
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var Security
     */
    private $security;


    public function __construct(EventDispatcherInterface $eventDispatcher, CompanyRepositoryInterface $repository, Security $security)
    {

        $this->eventDispatcher = $eventDispatcher;
        $this->repository = $repository;
        $this->entityManager = $repository->getEntityManager();
        $this->security = $security;
    }

    public function list()
    {
        return $this->repository->findAllOrdered(true);
    }

    public function createObject()
    {
        return new Company($this->security->getUser()->getUsername());
    }

    public function handleCreate(Company $company)
    {
        $this->save($company);
        $this->eventDispatcher->dispatch(new CompanyCreatedEvent($company, $this->security->getUser()));
    }

    public function handleEdit(Company $company)
    {
        $company->updatedBy = $this->security->getUser()->getUsername();
        $this->save($company);

        $this->eventDispatcher->dispatch(new CompanyEditedEvent($company, $this->security->getUser()));
    }

    public function handleDelete(Company $company)
    {
        $this->entityManager->remove($company);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new CompanyDeletedEvent($company, $this->security->getUser()));
    }

    public function handleShare(Company $company)
    {
        $this->eventDispatcher->dispatch(new CompanySharedEvent($company, $this->security->getUser()));
    }

    private function save(Company $company)
    {
        $this->entityManager->persist($company);
        $this->entityManager->flush();
    }
}