<?php


namespace EventBundle\Enums;


class RepeatingType
{
    const WEEKLY         = 1;
    const MONTHLY        = 2;


    public static function getList(){
        $oClass = new \ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}