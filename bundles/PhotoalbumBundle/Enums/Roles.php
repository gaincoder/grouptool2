<?php


namespace PhotoalbumBundle\Enums;


use App\Interfaces\RoleEnumInterface;

class Roles implements RoleEnumInterface
{
    const ROLE_PHOTOALBUM_CREATE         = 'ROLE_PHOTOALBUM_CREATE';
    const ROLE_PHOTOALBUM_EDIT           = 'ROLE_PHOTOALBUM_EDIT';
    const ROLE_PHOTOALBUM_DELETE         = 'ROLE_PHOTOALBUM_DELETE';
    const ROLE_PHOTO_DELETE              = 'ROLE_PHOTO_DELETE';
    const ROLE_PHOTO_UPLOAD              = 'ROLE_PHOTO_UPLOAD';



    public static function getList(){
        $oClass = new \ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}