<?php


namespace CompanyBundle\Enums;


use App\Interfaces\RoleEnumInterface;

class Roles implements RoleEnumInterface
{
    const ROLE_COMPANY_VIEWLIST       = 'ROLE_COMPANY_VIEWLIST';
    const ROLE_COMPANY_CREATE         = 'ROLE_COMPANY_CREATE';
    const ROLE_COMPANY_EDIT           = 'ROLE_COMPANY_EDIT';
    const ROLE_COMPANY_DELETE         = 'ROLE_COMPANY_DELETE';



    public static function getList(){
        $oClass = new \ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}