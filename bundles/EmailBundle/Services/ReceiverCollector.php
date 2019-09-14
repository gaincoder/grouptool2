<?php


namespace EmailBundle\Services;


use App\Entity\Group;
use App\Entity\User;
use App\Interfaces\GroupVisbilityInterface;
use App\Repository\UserRepositoryInterface;
use Symfony\Component\Security\Core\Security;

class ReceiverCollector
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getReceivers(GroupVisbilityInterface $item,$email)
    {
        $group = $item->getGroup();
        if($group instanceof Group){
            $users = $group->users->toArray();
        }else{
            $users = $this->userRepository->findAllActive();
        }
        $receivers = array_filter($users,function (User $user) use ($email){
            return in_array($email,$user->mails) && $user->isEnabled();
        });
        return $receivers;
    }
}