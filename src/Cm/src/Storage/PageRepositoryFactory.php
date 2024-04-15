<?php

declare(strict_types=1);

namespace Cm\Storage;

use Axleus\Db;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\TableGateway\Feature\FeatureSet;
use Laminas\Db\TableGateway\Feature\RowGatewayFeature;
use Psr\Container\ContainerInterface;

final class PageRepositoryFactory
{
    public function __invoke(ContainerInterface $container): PageRepository
    {
        $adapter = $container->get(AdapterInterface::class);
        $ref = new Db\Feature\RelatedTable\RelatedTableReference();
        $ref->addReference([
            'dependent_table' => $container->get(PageDataRepository::class),
            'ref_type'        => Db\Feature\RelatedTable\AbstractTableReference::REF_DEPENDENT,
            'column_map'      => ['local' => 'id', 'fk' => 'pageId'],
        ]);
        $rowGatewayPrototype = new PageEntity('id', 'page', $adapter);
        $rowGatewayPrototype->setReferenceProvider($ref);
        return new PageRepository(
            new Db\TableGateway(
                'page',
                $adapter,
                new FeatureSet([
                    new Db\Feature\ScrollablePdoResultFeature(),
                    new RowGatewayFeature($rowGatewayPrototype)
                ]),
            )
        );
    }
}
