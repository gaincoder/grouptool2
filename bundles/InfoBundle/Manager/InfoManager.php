<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 03.07.2019
 * Time: 23:29
 */

namespace InfoBundle\Manager;


use Doctrine\ORM\EntityManagerInterface;
use InfoBundle\Entity\Info;
use InfoBundle\Event\InfoCreatedEvent;
use InfoBundle\Event\InfoDeletedEvent;
use InfoBundle\Event\InfoEditedEvent;
use InfoBundle\Event\InfoSharedEvent;
use InfoBundle\Repository\InfoRepositoryInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class InfoManager implements InfoManagerInterface
{

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var InfoRepositoryInterface
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


    public function __construct(EventDispatcherInterface $eventDispatcher, InfoRepositoryInterface $repository, Security $security)
    {

        $this->eventDispatcher = $eventDispatcher;
        $this->repository = $repository;
        $this->entityManager = $repository->getEntityManager();
        $this->security = $security;
    }

    public function list()
    {
        return $this->repository->findAllOrdered();
    }

    public function createObject()
    {
        return new Info($this->security->getUser()->getUsername());
    }

    public function handleCreate(Info $info)
    {
        $this->save($info);
        $this->eventDispatcher->dispatch(new InfoCreatedEvent($info, $this->security->getUser()));
    }

    public function handleEdit(Info $info)
    {
        $info->updatedBy = $this->security->getUser()->getUsername();
        $this->save($info);

        $this->eventDispatcher->dispatch(new InfoEditedEvent($info, $this->security->getUser()));
    }

    public function handleDelete(Info $info)
    {
        $this->entityManager->remove($info);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new InfoDeletedEvent($info, $this->security->getUser()));
    }

    public function handleShare(Info $info)
    {
        $this->eventDispatcher->dispatch(new InfoSharedEvent($info, $this->security->getUser()));
    }

    private function save(Info $info)
    {
        $this->entityManager->persist($info);
        $this->entityManager->flush();
    }
}