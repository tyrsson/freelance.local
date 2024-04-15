<?php

declare(strict_types=1);

namespace App\Form\Fieldset;

use Cm\Storage\Repository;
use Laminas\Form\Element\Checkbox;
use Limatus\Form;

class SettingsFieldset extends Form\Fieldset
{
    public function __construct(
        protected Repository $repository,
        $name = 'settings',
        $options = []
    ) {
        parent::__construct($name, $options);
    }

    public function init(): void
    {
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
                //'placeholder' => 'Password',
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
            'type'    => Checkbox::class,
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
