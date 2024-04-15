<?php

declare(strict_types=1);

namespace Cm\List\Form\Fieldset;

use Cm\Storage\ListEntity;
use Cm\Storage\PrimaryKey;
use Cm\Storage\Schema;
use Laminas\Db\Adapter\AdapterAwareInterface;
use Laminas\Db\Adapter\AdapterAwareTrait;
use Laminas\Hydrator\ArraySerializableHydrator;
use Laminas\InputFilter\InputFilterProviderInterface;
use Limatus\Form;

final class ListFieldset extends Form\Fieldset implements InputFilterProviderInterface, AdapterAwareInterface
{
    use AdapterAwareTrait;

    public function __construct($name = 'list-data', $options = [])
    {
        parent::__construct($name, $options);
    }

    public function init()
    {
        $this->setHydrator(new ArraySerializableHydrator());
        $this->setObject(
            new ListEntity(
                PrimaryKey::List->value,
                Schema::List->value,
                $this->adapter
            )
        );
    }

    public function getInputFilterSpecification()
    {

    }
}
