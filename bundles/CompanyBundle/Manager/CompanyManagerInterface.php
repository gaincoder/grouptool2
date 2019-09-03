<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 03.07.2019
 * Time: 23:42
 */

namespace CompanyBundle\Manager;

use CompanyBundle\Entity\Company;

interface CompanyManagerInterface
{
    public function list();

    /**
     * @return Company
     */
    public function createObject();

    public function handleCreate(Company $company);

    public function handleEdit(Company $company);

    public function handleDelete(Company $company);

    public function handleShare(Company $company);
}