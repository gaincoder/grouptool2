<?php


namespace EventBundle\Enums;


use App\Interfaces\RoleEnumInterface;

class Roles implements RoleEnumInterface
{
    const ROLE_EVENT_CREATE                  = 'ROLE_EVENT_CREATE';
    const ROLE_EVENT_EDIT                    = 'ROLE_EVENT_EDIT';
    const ROLE_EVENT_DELETE                  = 'ROLE_EVENT_DELETE';
    const ROLE_REPEATINGEVENT_CREATE         = 'ROLE_REPEATINGEVENT_CREATE';
    const ROLE_REPEATINGEVENT_EDIT           = 'ROLE_REPEATINGEVENT_EDIT';
    const ROLE_REPEATINGEVENT_DELETE         = 'ROLE_REPEATINGEVENT_DELETE';
    const ROLE_REPEATINGEVENT_VIEW           = 'ROLE_REPEATINGEVENT_VIEW';
    const ROLE_SEND_NOTIFICATIONS            = 'ROLE_SEND_NOTIFICATIONS';



    public static function getList(){
        $oClass = new \ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}