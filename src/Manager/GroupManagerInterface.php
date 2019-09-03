<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 03.07.2019
 * Time: 23:42
 */

namespace App\Manager;

use App\Entity\Group;

interface GroupManagerInterface
{
    public function list();

    /**
     * @return Group
     */
    public function createObject();

    public function handleCreate(Group $group);

    public function handleEdit(Group $group);

    public function handleDelete(Group $group);

}