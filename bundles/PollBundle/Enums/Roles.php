<?php


namespace PollBundle\Enums;


use App\Interfaces\RoleEnumInterface;

class Roles implements RoleEnumInterface
{
    const ROLE_POLL_CREATE         = 'ROLE_POLL_CREATE';
    const ROLE_POLL_EDIT           = 'ROLE_POLL_EDIT';
    const ROLE_POLL_DELETE         = 'ROLE_POLL_DELETE';
    const ROLE_POLL_CLOSE          = 'ROLE_POLL_CLOSE';
    const ROLE_POLL_OPEN           = 'ROLE_POLL_OPEN';
    const ROLE_POLL_VIEW_VISIBILITY         = 'ROLE_POLL_VIEW_VISIBILITY';



    public static function getList(){
        $oClass = new \ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}