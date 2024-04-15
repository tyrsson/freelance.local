<?php

declare(strict_types=1);

namespace Cm\Storage;

use Axleus\Db\AbstractRepository;
use Axleus\Db\RepositoryTrait;
use Laminas\Stdlib\ArrayObject;

final class SettingsRepository extends AbstractRepository
{
    use RepositoryTrait;

    public function fetchContext()
    {
        $result = $this->gateway->select();
        $container = new ArrayObject([], ArrayObject::ARRAY_AS_PROPS);
        foreach ($result as $row) {
            foreach ($row as $column) {
                $container->variable = $column->variable;
            }
        }
        return $container;
    }
}
