<?php


namespace App\Enums;


class Roles
{

    const ROLE_MANAGE_COMPANIES         = 'ROLE_MANAGE_COMPANIES';
    const ROLE_MANAGE_USER              = 'ROLE_MANAGE_USER';
    const ROLE_MANAGE_USER_ALL          = 'ROLE_MANAGE_USER_ALL';




    public static function getList(){

        $oClass = new \ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}