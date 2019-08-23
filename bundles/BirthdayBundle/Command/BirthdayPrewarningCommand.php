<?php


namespace BirthdayBundle\Command;


use BirthdayBundle\Entity\Birthday;
use Doctrine\ORM\EntityManagerInterface;
use BirthdayBundle\Event\BirthdayEvent;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class BirthdayPrewarningCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'birthday:prewarning';

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
            ->setDescription('Trigger BirthdayPrewarning Event')
            ->addOption('weeks', 'w', InputOption::VALUE_OPTIONAL, 'Weeks before birthday', 2);

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $birthdays = $this->em->getRepository(Birthday::class)->findInWeeks($input->getOption('weeks'));
        $output->writeln(count($birthdays) . ' Birthdays found');
        foreach ($birthdays as $birthday) {
            $this->dispatcher->dispatch(new BirthdayEvent($birthday), BirthdayEvent::NAME_PREWARNING);
        }
    }
}