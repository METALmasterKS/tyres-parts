<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Catalog\Paginator\Adapter;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\ResultSet\ResultSetInterface;

class MoreDbSelect implements \Zend\Paginator\Adapter\AdapterInterface
{
    const ROW_COUNT_COLUMN_NAME = 'C';

    /**
     * @var Sql
     */
    protected $sql;

    /**
     * Database query
     *
     * @var Select
     */
    protected $select;

    /**
     * @var ResultSet
     */
    protected $resultSetPrototype;

    /**
     * Total item count
     *
     * @var int
     */
    protected $rowCount;
    
    protected $idField;

    /**
     * Constructor.
     *
     * @param Select $select The select query
     * @param Adapter|Sql $adapterOrSqlObject DB adapter or Sql object
     * @param null|ResultSetInterface $resultSetPrototype
     * @param null|Select $countSelect
     *
     * @throws Exception\InvalidArgumentException
     */
    public function __construct(
        Select $select,
        $idField,    
        $adapterOrSqlObject,
        ResultSetInterface $resultSetPrototype = null
    ) {
        $this->select = $select;
        $this->idField = $idField;
        if ($adapterOrSqlObject instanceof Adapter) {
            $adapterOrSqlObject = new Sql($adapterOrSqlObject);
        }

        if (!$adapterOrSqlObject instanceof Sql) {
            throw new Exception\InvalidArgumentException(
                '$adapterOrSqlObject must be an instance of Zend\Db\Adapter\Adapter or Zend\Db\Sql\Sql'
            );
        }

        $this->sql                = $adapterOrSqlObject;
        $this->resultSetPrototype = ($resultSetPrototype) ?: new ResultSet;
    }

    /**
     * Returns an array of items for a page.
     *
     * @param  int $lastId           Page offset
     * @param  int $itemCountPerPage Number of items per page
     * @return array
     */
    public function getItems($lastId, $itemCountPerPage)
    {
        $select = clone $this->select;
        $select->limit($itemCountPerPage + 1);
        if (isset($lastId))
            $select->where->greaterThan($this->idField, $lastId);
        
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        $resultSet = clone $this->resultSetPrototype;
        $resultSet->initialize($result);
        
        $this->rowCount = $resultSet->count();
        
        return array_slice(iterator_to_array($resultSet), 0, $itemCountPerPage);
    }

    /**
     * Returns the total number of rows in the result set.
     *
     * @return int
     */
    public function count()
    {
        return $this->rowCount;
    }


}
