<?php


namespace App\Enums;


class Roles
{

    const ROLE_GROUP_CREATE         = 'ROLE_GROUP_CREATE';
    const ROLE_GROUP_EDIT           = 'ROLE_GROUP_EDIT';
    const ROLE_GROUP_DELETE         = 'ROLE_GROUP_DELETE';
    const ROLE_GROUP_VIEWLIST       = 'ROLE_GROUP_VIEWLIST';

    const ROLE_USER_EDIT           = 'ROLE_USER_EDIT';
    const ROLE_USER_VIEWLIST       = 'ROLE_USER_VIEWLIST';
    const ROLE_USER_APPROVE        = 'ROLE_USER_APPROVE';
    const ROLE_MANAGE_ALL_USER        = 'ROLE_MANAGE_ALL_USER';



    public static function getList(){

        $oClass = new \ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}