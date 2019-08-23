<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 03.07.2019
 * Time: 23:42
 */

namespace InfoBundle\Manager;

use InfoBundle\Entity\Info;

interface InfoManagerInterface
{
    public function list();

    public function createObject();

    public function handleCreate(Info $info);

    public function handleEdit(Info $info);

    public function handleDelete(Info $info);

    public function handleShare(Info $info);
}