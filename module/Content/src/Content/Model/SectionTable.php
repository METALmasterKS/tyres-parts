<?php
namespace Content\Model;
 
use Zend\Db\TableGateway\TableGateway as TableGateway;

use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
 
class SectionTable extends TableGateway
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
    
    public function getSection($id)
    {
        $id  = (int) $id;
        $resultSet = $this->tableGateway->select(['id' => $id]);
        return $resultSet->current();
    }
    
    public function getSectionByAlias($alias)
    {
        $resultSet = $this->tableGateway->select(['alias' => $alias]);
        return $resultSet->current();
    }
    
    public function getSections($params = array())
    {
        //основной запрос
        $select = $this->tableGateway->getSql()->select();
        $select ->columns(array('*'));
        
        if (isset($params['name']))
            $select->where->like('name', $params['name']);

        if (isset($params['alias']))
            $select->where->like('aliases', $params['alias']);
        
        
        if (isset($params['parentId']))
            $select->where->equalTo('parentId', $params['parentId']);
        
        //getByIds
        if (isset($params['ids']))
            $select->where->in('id', $params['ids']);
        
        //порядок
        if (isset($params['order']))
            $select->order($params['order']);
        
        //pagination flag
        if (isset($params['pagination']) && (bool) $params['pagination']) {
            return new Paginator(new DbSelect($select, $this->tableGateway->getAdapter(), $this->tableGateway->getResultSetPrototype()));
        } else {
            $resultSet = $this->tableGateway->selectWith($select);
            $resultSet->buffer();
            return $resultSet;
        }
    }
    
    public function saveSection(\Content\Model\Section $section){
        $data = get_object_vars($section);
        $id = $section->id;
        
        if ($id == null) 
            return $this->tableGateway->insert($data);
        else//if ($this->isExist($id)) 
            return $this->tableGateway->update($data, array('id' => $id));
        
        return FALSE;
    }
    
    public function deleteSection($ids)
    {
        $this->tableGateway->delete(array('id' => $ids));
    }
    
    public function saveSorting($parentId, $sort) {
        $dbAdapter = $this->tableGateway->getAdapter();
        
        $dbAdapter->getDriver()->getConnection()->beginTransaction();
        $sql = new \Zend\Db\Sql\Sql($this->tableGateway->getAdapter());
        $insert = $sql->insert($this->tableGateway->getTable())->columns(array('id' => 'id', 'parentId' => 'parentId', 'sort' => 'sort'));
        if (is_array($sort) && count($sort) > 0) {
            foreach ($sort as $ordVal => $id) {
                $insert->values(['id' => $id, 'parentId' => $parentId, 'sort' => $ordVal]);
                $insertSql = $sql->getSqlStringForSqlObject($insert)
                    .' ON DUPLICATE KEY UPDATE '
                    .sprintf('%1$s = VALUES(%1$s)', $dbAdapter->getPlatform()->quoteIdentifier('sort'));
                $dbAdapter->query($insertSql, $dbAdapter::QUERY_MODE_EXECUTE);
            }
        }
        $dbAdapter->getDriver()->getConnection()->commit();
        
    }
    
}