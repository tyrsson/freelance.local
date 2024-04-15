<?php

declare(strict_types=1);

namespace Cm\Storage;

use Axleus\Db;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\ResultSet\ResultSetInterface;
use Laminas\Db\Sql;

final class PageRepository extends Db\AbstractRepository
{
    use Db\RepositoryTrait;

    private string $dependentTable = 'page_data';
    private string $dependentFk    = 'pageId';
    private string $pk             = 'id';

    public function findAttachedPages(
        ?string $title = 'home',
        array $columns = ['*'],
        bool $onlyActive = true,
        bool $returnArray = false
    ): ResultSetInterface|array {

        $where = new Sql\Where();
        if ($title !== 'home') {
            $where->equalTo('title', $title);
        }
        if ($title === 'home') {
            $where->equalTo('showOnHome', '1');
        }
        if ($onlyActive) {
            $where->equalTo('active', '1');
        }
        $select = $this->gateway->getSql()->select();
        $select->columns($columns);
        $select->where($where);
        /** @var HydratingResultSet */
        $resultSet = $this->gateway->selectWith($select);
        if (! $returnArray) {
            return $resultSet;
        }
        return $resultSet->toArray();
    }

    public function findMenu(
        array $columns = ['*'],
        bool $onlyActive = true,
        bool $returnArray = false
    ): ResultSetInterface|array {
        $where = new Sql\Where();
        if ($onlyActive) {
            $where->equalTo('active', '1');
        }
        $where->equalTo('showInMenu', '1');
        $select = $this->gateway->getSql()->select();
        $select->columns($columns);
        $select->where($where);
        /** @var HydratingResultSet */
        $resultSet = $this->gateway->selectWith($select);
        if (! $returnArray) {
            return $resultSet;
        }
        return $resultSet->toArray();
    }
}
