<?php

namespace App\Manager;

use App\Entity\User;

interface UserManagerInterface
{
    public function list();

    public function createObject();

    public function handleCreate(User $user);

    public function handleEdit(User $user);

    public function handleDelete(User $user);
}