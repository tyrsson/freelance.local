<?php

declare(strict_types=1);

namespace Cm\List\Form\Fieldset;

use Cm\Storage\ListItemsEntity;
use Cm\Storage\PrimaryKey;
use Cm\Storage\Schema;
use Limatus\Form;
use Laminas\Db\Adapter\AdapterAwareInterface;
use Laminas\Db\Adapter\AdapterAwareTrait;
use Laminas\Hydrator\ArraySerializableHydrator;
use Laminas\InputFilter\InputFilterProviderInterface;

final class ListItemsFieldset extends Form\Fieldset implements InputFilterProviderInterface, AdapterAwareInterface
{
    use AdapterAwareTrait;

    public function __construct($name = 'list-items-data', $options = [])
    {
        parent::__construct($name, $options);
    }

    public function init(): void
    {
        $this->setHydrator(new ArraySerializableHydrator());
        $this->setObject(
            new ListItemsEntity(
                PrimaryKey::Pk->value,
                Schema::ListItems->value,
                $this->adapter
            )
        );

    }

    public function getInputFilterSpecification(): array
    {
        return [];
    }
}
