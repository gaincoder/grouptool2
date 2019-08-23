<?php


namespace TelegramBundle\Services;


class MessageParser
{

    private $message;

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }


    public function isExactText($searchText): bool
    {
        if($this->isText()){
            return trim(strtolower($this->message->message->text)) === strtolower($searchText);
        }
        return false;
    }

    public function startsWithText($searchText): bool
    {
        if($this->isText()){
            return strpos(trim(strtolower($this->message->message->text)),strtolower($searchText)) === 0;
        }
        return false;
    }

    private function isText()
    {
        return isset($this->message->message) && isset($this->message->message->text) && strlen
            ($this->message->message->text) > 0;
    }



}