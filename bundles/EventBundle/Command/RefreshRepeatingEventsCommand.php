<?php


namespace EventBundle\Command;


use BirthdayBundle\Entity\Birthday;
use BirthdayBundle\Event\BirthdayEvent;
use Doctrine\ORM\EntityManagerInterface;
use EventBundle\Entity\Event;
use EventBundle\Event\EventReminderEvent;
use EventBundle\Services\RepeatingEvents;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RefreshRepeatingEventsCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'event:refreshRepeating';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Refresh repeating Event');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $repo = $this->em->getRepository('EventBundle:RepeatingEvent');
        $repeatingEvents = $repo->findAll();
        foreach ($repeatingEvents as $repeatingEvent){
            $output->writeln("Updating \"$repeatingEvent->name\"");
            $service = new RepeatingEvents($this->em);
            $service->setIsCronjon(true);
            $service->updateEvents($repeatingEvent);
        }
    }
}