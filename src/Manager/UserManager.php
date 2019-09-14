<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 03.07.2019
 * Time: 23:29
 */

namespace App\Manager;


use App\Event\UserApprovedEvent;
use App\Event\UserRefusedEvent;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use EmailBundle\Enums\Mails;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class UserManager implements UserManagerInterface
{

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var UserRepositoryInterface
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


    public function __construct(EventDispatcherInterface $eventDispatcher, UserRepositoryInterface $repository, Security $security)
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
        return new User($this->security->getUser()->getUsername());
    }

    public function handleCreate(User $user)
    {
        $this->save($user);
    }

    public function handleEdit(User $user)
    {
        $user->updatedBy = $this->security->getUser()->getUsername();
        $this->save($user);

    }

    public function handleDelete(User $user)
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();

    }


    private function save(User $user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function handleApproved(User $user)
    {
        $user->approval = 1;
        $user->setEnabled(true);
        $user->mails = Mails::getList();
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->eventDispatcher->dispatch(new UserApprovedEvent($user));
    }

    public function handleRefusal(User $user)
    {
        $user->approval = 2;
        $user->setEnabled(false);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->eventDispatcher->dispatch(new UserRefusedEvent($user));
    }
}