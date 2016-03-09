<?php
namespace Tyres\Model;
 
use Zend\Db\TableGateway\TableGateway as TableGateway;

use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
 
class ProviderTable extends TableGateway
{
    protected $tableGateway;
 
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
 
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        
        return $resultSet;
    }
    
    public function getProvider($id)
    {
        $id  = (int) $id;
        $resultSet = $this->tableGateway->select(['id' => $id]);
        return $resultSet->current();
    }
    
    public function getProviders($params = array())
    {
        //основной запрос
        $select = $this->tableGateway->getSql()->select();
        $select ->columns(array('*'));
        
        if (isset($params['name']))
            $select->where->like('name', $params['name']);
        //getByIds
        if (isset($params['ids']))
            $select->where->in('id', $params['ids']);
        //pagination flag
        if (isset($params['pagination']) && (bool) $params['pagination']) {
            return new Paginator(new DbSelect($select, $this->tableGateway->getAdapter(), $this->tableGateway->getResultSetPrototype()));
        } else {
            $resultSet = $this->tableGateway->selectWith($select);
            $resultSet->buffer();
            return $resultSet;
        }
    }
    
}