<?php

declare(strict_types=1);

namespace Cm\Storage;

use Axleus\Db;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\ResultSet\ResultSetInterface;
use Laminas\Db\Sql\Join;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;

final class PartialRepository extends Db\AbstractRepository
{
    use Db\RepositoryTrait;

    private string $dependentTable = 'tpl_partial_data';
    private string $dependentFk    = 'partialId';

    public function findAttachedPages(
        ?string $title = 'home',
        array $columns = ['*'],
        bool $onlyActive = true,
        bool $returnArray = false
    ): ResultSetInterface|array {

        $where = new Where();
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
        $where = new Where();
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

    // todo: let off in this method
    public function findPartialWithData(string $sectionId, array $columns = [Select::SQL_STAR])
    {
        $where = new Where();
        $where->equalTo('sectionId', $sectionId);
        $select = new Select();
        $select->from(['p' => $this->gateway->getTable()]);
        $select->where($where);
        $select->join(
            ['d' => $this->dependentTable],
            'p.id = d.' . $this->dependentFk,
            $columns,
            Join::JOIN_INNER
        );
       $resultSet = $this->gateway->selectWith($select);
       return $resultSet;
    }
}
