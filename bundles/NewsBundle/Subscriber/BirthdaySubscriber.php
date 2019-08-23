<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 10.06.2019
 * Time: 22:52
 */

namespace NewsBundle\Subscriber;


use BirthdayBundle\Event\BirthdayEvent;
use NewsBundle\Entity\News;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;


class BirthdaySubscriber implements EventSubscriberInterface
{


    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var RouterInterface
     */
    private $router;


    public function __construct(EntityManagerInterface $em, RouterInterface $router)
    {
        $this->em = $em;
        $this->router = $router;

    }


    public static function getSubscribedEvents()
    {
        return [
            BirthdayEvent::NAME_TODAY => 'onBirthdayToday',
            BirthdayEvent::NAME_PREWARNING => 'onBirthdayPrewarning'
        ];
    }


    public function onBirthdayToday(BirthdayEvent $event)
    {
        $birthday = $event->getBirthday();
        $news = new News('Geburtstag', $birthday->name . ' wird heute ' . $birthday->getAgeThisYear() . " Jahre alt!");
        $this->em->persist($news);
        $this->em->flush();

    }

    public function onBirthdayPrewarning(BirthdayEvent $event)
    {
        $birthday = $event->getBirthday();
        $news = new News('Ankündigung Geburtstag', 'Am ' . $birthday->birthdate->format('d.m.') . ' wird ' . $birthday->name . ' ' . $birthday->getNextAge() . " Jahre alt.");
        $this->em->persist($news);
        $this->em->flush();

    }
}