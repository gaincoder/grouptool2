<?php


namespace App\Enums;


class Roles
{
    public static function getList(){
        return [
            'ROLE_MANAGE_COMPANIES' => 'Firmen verwalten',
            'ROLE_MANAGE_USER' => 'Benutzer der eigenen Firma verwalten',
            'ROLE_MANAGE_ALL_USER' => 'Alle Benutzer verwalten',
        ];
    }
}