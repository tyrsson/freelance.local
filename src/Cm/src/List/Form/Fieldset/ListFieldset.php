<?php

declare(strict_types=1);

namespace Cm\List\Form\Fieldset;

use Cm\Storage\ListEntity;
use Cm\Storage\PrimaryKey;
use Cm\Storage\Schema;
use Laminas\Db\Adapter\AdapterAwareInterface;
use Laminas\Db\Adapter\AdapterAwareTrait;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Hydrator\ArraySerializableHydrator;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Form;
use Laminas\Form\Element\Textarea;
use Laminas\Form\View\Helper\FormLabel;
use Limatus\Vendor\InputType;
use Limatus\Vendor\Bootstrap\LayoutMode;
use Limatus\Vendor\Bootstrap\CssProviderTrait;
use Limatus\VendorInterface;

final class ListFieldset extends Form\Fieldset implements InputFilterProviderInterface, AdapterAwareInterface
{
    use AdapterAwareTrait;
    use CssProviderTrait;

    public function __construct($name = 'list-data', $options = [])
    {
        parent::__construct($name, $options);
    }

    public function init()
    {
        $this->setAttribute('class', 'row g-3'); // If we are using a fieldset the row needs set
        $this->setOptions([
            'label' => 'Create List',
            'label_attributes' => [
                'class' => 'col-form-label',
            ],
        ]);
        $this->setHydrator(new ArraySerializableHydrator());
        $this->setObject(
            new ListEntity(
                PrimaryKey::Pk->value,
                Schema::List->value,
                $this->adapter
            )
        );
        $this->add([
            'name' => 'id',
            'type' => Hidden::class,
        ])->add([
            'name' => 'parentId',
            'type' => Hidden::class,
        ]);
        $this->add([
            'name' => 'listName',
            'type' => Text::class,
            'attributes' => [
                'class' => $this->getClass(Text::class),
            ],
            'options' => [
                'label' => 'List Name',
                'label_attributes' => [
                    'class' => 'form-label',
                ],
                'label_options' => [
                    'position' => FormLabel::PREPEND,
                ],
                VendorInterface::class => [
                    'row' => 'row mb-3',
                    'column' => 'col-sm-6',
                    'layout_mode' => LayoutMode::Grid,
                    'help' => 'The name of the list',
                    'help_attributes' => [
                        'class' => 'form-text text-muted col-sm-10',
                    ],
                ],
            ],
        ]);

        $this->add([
            'name' => 'parentType',
            'type' => Select::class,
            'attributes' => [ // move this to the TwBs
                'id' => 'parentType',
                'class' => $this->getClass(InputType::Select),
            ],
            'options' => [
                'label' => 'Parent Type',
                'empty_option' => 'Please select a Parent Type',
                'value_options' => [
                    '0' => 'Skills',
                    '1' => 'Faq',
                    '3' => 'About',
                ],
                'label_attributes' => [
                    'class' => 'form-label',
                ],
                'label_options' => [
                    'position' => FormLabel::PREPEND,
                ],
                VendorInterface::class => [
                    'column' => 'col-sm-6',
                    'layout_mode' => LayoutMode::Grid,
                ],
            ],
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [];
    }
}
