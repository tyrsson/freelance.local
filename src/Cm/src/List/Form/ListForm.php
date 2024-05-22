<?php

declare(strict_types=1);

namespace Cm\List\Form;

use Laminas\Hydrator\ArraySerializableHydrator;
use Laminas\InputFilter\InputFilter;
use Laminas\Form;
use Laminas\Form\Element;
use Limatus\Form\FormTrait;
use Limatus\Vendor\Bootstrap\LayoutMode;
use Limatus\VendorInterface;
use Limatus\Vendor\Bootstrap\Style;
use Limatus\Vendor\Bootstrap\CssProviderTrait;

final class ListForm extends Form\Form
{
    use CssProviderTrait;
    use FormTrait;

    public function __construct($name = 'list', $options = ['layout_mode' => LayoutMode::Horizontal])
    {
        parent::__construct($name, $options);
    }

    public function init(): void
    {
        $mode = $this->getOption('layout_mode');
        $this->setAttributes([
            'method' => 'POST',
        ]);
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
        $this->addSubmit(
            priority: -1,
            label: 'Save List',
            class: 'btn btn-primary',
        );
    }
}
