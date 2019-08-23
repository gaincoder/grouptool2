<?php


namespace EventBundle\Command;


use BirthdayBundle\Entity\Birthday;
use BirthdayBundle\Event\BirthdayEvent;
use Doctrine\ORM\EntityManagerInterface;
use EventBundle\Entity\Event;
use EventBundle\Event\EventReminderEvent;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventReminderCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'event:reminder';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Trigger EventReminder Event');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $events = $this->em->getRepository(Event::class)->findNextThree();
        $this->dispatcher->dispatch(new EventReminderEvent($events));
    }
}