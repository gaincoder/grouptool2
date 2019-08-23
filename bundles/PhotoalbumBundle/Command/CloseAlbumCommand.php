<?php


namespace PhotoalbumBundle\Command;


use Doctrine\ORM\EntityManagerInterface;
use FilesystemIterator;
use PhotoalbumBundle\Entity\Photoalbum;
use PhotoalbumBundle\Event\PhotoalbumClosedEvent;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CloseAlbumCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'photoalbum:closealbum';

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
            ->setDescription('Close open Photoalbum');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dir = dirname(__FILE__) . '/../../../var/logs/active_album/';
        $output->writeln('Looking in \''.$dir.'\'');
        if (file_exists($dir)) {
            $fi = new FilesystemIterator($dir, FilesystemIterator::SKIP_DOTS);
            if (iterator_count($fi) > 0) {
                foreach ($fi as $file) {
                    if ((filemtime($file) + 0) < time()) {
                        unlink($file);
                        $album = $this->em->getRepository(Photoalbum::class)->find(basename($file));
                        $this->dispatcher->dispatch(new PhotoalbumClosedEvent($album));
                        $output->writeln('Album \''.$album->name.'\' closed');
                    }
                }
            }
        }
    }
}