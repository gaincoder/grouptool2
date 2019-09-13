<?php


namespace App\Services;


class RoleCollector
{
    /**
     * @var \App\Interfaces\RoleEnumInterface[]
     */
    private $enums;

    public function __construct(iterable $enums)
    {
        $this->enums = $enums;
    }

    public function getList()
    {
        $entries = [];
        foreach ($this->enums as $enum) {
            $entries = array_merge($entries,$enum::getList());
        }
        return $entries;
    }
}