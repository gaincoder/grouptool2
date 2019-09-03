<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 03.07.2019
 * Time: 23:29
 */

namespace App\Manager;


use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Group;
use App\Event\GroupCreatedEvent;
use App\Event\GroupDeletedEvent;
use App\Event\GroupEditedEvent;
use App\Event\GroupSharedEvent;
use App\Repository\GroupRepositoryInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class GroupManager implements GroupManagerInterface
{

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var GroupRepositoryInterface
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


    public function __construct(EventDispatcherInterface $eventDispatcher, GroupRepositoryInterface $repository, Security $security)
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
        return new Group($this->security->getUser()->getUsername());
    }

    public function handleCreate(Group $group)
    {
        $this->save($group);
    }

    public function handleEdit(Group $group)
    {
        $group->updatedBy = $this->security->getUser()->getUsername();
        $this->save($group);

    }

    public function handleDelete(Group $group)
    {
        $this->entityManager->remove($group);
        $this->entityManager->flush();

    }


    private function save(Group $group)
    {
        $this->entityManager->persist($group);
        $this->entityManager->flush();
    }
}