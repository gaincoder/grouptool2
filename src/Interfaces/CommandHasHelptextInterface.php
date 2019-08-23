<?php
/**
 * Copyright Tim Moritz. All rights reserved.
 * Creator: tim
 * Date: 08/08/17
 * Time: 16:39
 */


namespace App\Interfaces;

use App\Services\TelegramBot;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class CommandInterface
 * @package App\BotCommands
 */
interface CommandHasHelptextInterface
{
    public function getHelptext(): string;

}