<?php

declare(strict_types=1);

namespace Cm\List\Form;

use Laminas\Hydrator\ArraySerializableHydrator;
use Laminas\InputFilter\InputFilter;
use Limatus\Form;

final class ListForm extends Form\Form
{
    public function __construct($name = 'list', $options = [])
    {
        parent::__construct($name, $options);
    }

    public function init(): void
    {
        $this->setAttribute('method', 'POST');
        $this->setHydrator(new ArraySerializableHydrator());
        $this->setInputFilter(new InputFilter());

        // add ListFieldset here
        $this->add([
            'type' => Fieldset\ListFieldset::class,
            'options' => [
                'use_as_base_fieldset' => true,
            ],
        ]);

        // add CSRF protection here.


        $this->addSubmit();
    }
}
