<?php


namespace EventBundle\Enums;


class RepeatingType
{
    const WEEKLY            = 1;
    const SECONDWEEKLY      = 2;
    const THIRDWEEKLY       = 3;
    const FOURWEEKLY        = 4;
    const MONTHLY           = 5;


    public static function getList(){
        $oClass = new \ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}