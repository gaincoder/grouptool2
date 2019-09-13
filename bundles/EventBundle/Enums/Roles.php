<?php


namespace EventBundle\Enums;


use App\Interfaces\RoleEnumInterface;

class Roles implements RoleEnumInterface
{
    const ROLE_EVENT_CREATE         = 'ROLE_EVENT_CREATE';
    const ROLE_EVENT_EDIT           = 'ROLE_EVENT_EDIT';
    const ROLE_EVENT_DELETE         = 'ROLE_EVENT_DELETE';



    public static function getList(){
        $oClass = new \ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}