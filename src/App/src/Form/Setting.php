<?php

declare(strict_types=1);

namespace App\Form;

use App\Form\Fieldset\LoginFieldset;
use Laminas\Filter;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Validator;
use Limatus\Form;

use function strtolower;

final class Setting extends Form\Form implements InputFilterProviderInterface
{
    protected $attributes = ['class' => 'login-form', 'method' => 'POST'];
    /**
     * $options['fieldset'] must be false since the Authentication component expects username and passoword
     * to be in the top level of the POST array
     * @param string $name
     * @param array $options
     * @return void
     * @throws InvalidArgumentException
     */
    public function __construct($name = 'setting-form', $options = ['mode' => self::HORIZONTAL_MODE, 'fieldset' => false])
    {
        parent::__construct($name, $options);
    }

    public function init(): void
    {
        $options = $this->getOptions();
        if ($options['fieldset']) {
            $manager = $this->getFormFactory()->getFormElementManager();
            $this->add(
                $manager->build(
                    LoginFieldset::class,
                    [
                        // 'label' => 'Horizontal Fieldset',
                        // 'label_attributes' => [
                        //     'class' => 'col-form-label col-sm-2 pt-0',
                        // ],
                    ]
                )
            );
        } else {
            $this->add([
                'name' => 'username',
                'type' => Form\Element\Text::class,
                'attributes' => [
                    'class' => 'form-control custom-class',
                    //'placeholder' => 'User Name',
                ],
                'options' => [
                    'label' => 'User Name',
                    'label_attributes'     => [
                        'class' => 'col-sm-2 col-form-label'
                    ],
                    'bootstrap_attributes' => [
                        'class' => 'row mb-3',
                    ],
                    'horizontal_attributes' => [
                        'class' => 'col-lg-6',
                    ],
                    'help'            => 'Your email address.',
                    'help_attributes' => [
                        'class' => 'form-text text-muted col-sm-10 offset-sm-2',
                    ],
                ],
            ]);
            $this->add([
                'name' => 'password',
                'type' => Form\Element\Password::class,
                'attributes' => [
                    'class' => 'form-control custom-class',
                    //'placeholder' => 'Email',
                ],
                'options' => [
                    'label' => 'Password',
                    'label_attributes'     => [
                        'class' => 'col-sm-2 col-form-label'
                    ],
                    'bootstrap_attributes' => [
                        'class' => 'row mb-3',
                    ],
                    'horizontal_attributes' => [
                        'class' => 'col-lg-6',
                    ],
                ],
            ]);
            $this->add([
                'name'    => 'session_length_override',
                'type'    => Form\Element\Checkbox::class,
                'attributes' => [
                    'value' => '0',
                    /**
                     * attribute used in the input class="form-check-input"
                     * the example-checkbox is a custom class for css targeting, see the style sheet for the demo site
                     */
                    'class' => 'form-check-input',
                ],
                'options' => [
                    'label' => 'Stay Logged In?',
                    'use_hidden_element' => false,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                    'bootstrap_attributes' => [
                        'class' => 'row', // used in the outer most wrapper div for checkbox
                    ],
                    'label_attributes' => [
                        'class' => 'form-check-label',
                    ],
                    /**
                     * used for the div wrapping the input wrapper which is always form-check
                     * @see \Bootstrap\Form\View\Helper\FormCheckbox
                     */
                    'horizontal_attributes' => [
                        'class' => 'col-sm-10 offset-sm-2',
                    ],
                    // we need the label after the input
                    'label_options' => [
                        'label_position' => 'APPEND',
                    ],
                    'help'            => 'Check this box to stay logged in.',
                    'help_attributes' => [
                        'class' => 'form-text text-muted col-sm-10 offset-sm-2',
                    ],
                ],
            ]);
        }
    }

    public function getInputFilterSpecification(): array
    {
        $options = $this->getOptions();
        $filter  = [
            'username' => [
                'required' => true,
                'filters'  => [
                    ['name' => Filter\StripTags::class],
                    ['name' => Filter\StringTrim::class],
                ],
                'validators' => [
                    [
                        'name'    => Validator\StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ],
                    ],
                ],
            ],
        ];
        if (! $options['fieldset']) {
            return $filter;
        } else {
            return [];
        }
    }
}
