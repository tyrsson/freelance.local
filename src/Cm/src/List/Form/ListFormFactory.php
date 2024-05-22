<?php

declare(strict_types=1);

namespace Cm\List\Form;

use Psr\Container\ContainerInterface;

final class ListFormFactory
{
    public function __invoke(ContainerInterface $container): ListForm
    {
        return new ListForm();
    }
}
