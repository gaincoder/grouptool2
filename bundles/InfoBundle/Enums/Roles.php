<?php


namespace InfoBundle\Enums;


use App\Interfaces\RoleEnumInterface;

class Roles implements RoleEnumInterface
{
    const ROLE_INFO_CREATE         = 'ROLE_INFO_CREATE';
    const ROLE_INFO_EDIT           = 'ROLE_INFO_EDIT';
    const ROLE_INFO_DELETE         = 'ROLE_INFO_DELETE';



    public static function getList(){
        $oClass = new \ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}