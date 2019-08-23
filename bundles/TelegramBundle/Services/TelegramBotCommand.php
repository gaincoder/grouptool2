<?php


namespace TelegramBundle\Services;


use App\Interfaces\CommandFireInterface;
use App\Interfaces\CommandInterface;
use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;


class TelegramBotCommand
{


    /**
     * @var CommandFireInterface[]
     */
    private $commands;

    public function __construct(iterable $commands)
    {
        $this->commands = $commands;
    }

    public function listCommandClasses()
    {
        return array_map("get_class",iterator_to_array($this->commands));
    }

    /**
     * @return CommandInterface[]
     */
    public function getCommands(): iterable
    {
        return $this->commands;
    }

    public function processMessage($rawMessage){
        $message = json_decode($rawMessage);
        if($message !== null) {
            foreach ($this->commands as $command) {
                if ($command->checkResponsibility($message)) {
                    $command->fire();
                    return;
                }
            }
        }
    }


}