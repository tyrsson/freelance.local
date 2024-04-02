<?php

declare(strict_types=1);

namespace App\Storage\Listener;

use Laminas\Db\TableGateway\Feature\EventFeature\TableGatewayEvent;
use Laminas\Db\TableGateway\Feature\EventFeatureEventsInterface as TargetEvent;
use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\EventManager\EventManagerInterface;

final class PartialListener extends AbstractListenerAggregate
{

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(
            TargetEvent::EVENT_PRE_SELECT,
            [$this, 'onPreSelect'],
            $priority
        );
    }

    public function onPreSelect(TableGatewayEvent $event)
    {
        $params = $event->getParams();
    }
}
