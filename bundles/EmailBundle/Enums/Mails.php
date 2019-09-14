<?php


namespace EmailBundle\Enums;


use App\Interfaces\RoleEnumInterface;

class Mails implements RoleEnumInterface
{
    const MAIL_POLL_NEW         = 'MAIL_POLL_NEW';
    const MAIL_EVENT_NEW        = 'MAIL_EVENT_NEW';




    public static function getList(){
        $oClass = new \ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}