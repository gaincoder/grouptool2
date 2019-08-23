<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 10.06.2019
 * Time: 23:22
 */

namespace App\Interfaces;

use TelegramBot\Api\BotApi;

interface TelegramBotInterface
{
    public function sendMessage($message, $replyToMessageId = null, $replyMarkup = null);

    /**
     * @return BotApi
     */
    public function getBot();

    /**
     * @return mixed
     */
    public function getBotId();

    public function getChats();
}