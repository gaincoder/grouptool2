<?php

namespace App\Interfaces;

use App\Entity\Group;

interface GroupVisbilityInterface{
    /**
     * @return Group
     */
    public function getGroup();
}