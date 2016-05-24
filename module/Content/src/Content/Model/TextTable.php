<?php
namespace Content\Model;
 
use Zend\Db\TableGateway\TableGateway as TableGateway;
 
class TextTable extends TableGateway
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
 
    public function getText($id) {
        $id  = (int) $id;
        $resultSet = $this->tableGateway->select(['id' => $id]);
        return $resultSet->current();
    }
    
    public function getTextByAlias($alias) {
        $resultSet = $this->tableGateway->select(['alias' => $alias]);
        return $resultSet->current();
    }
    
    public function getTexts($params = null) 
    {
        //основной запрос
        $select = $this->tableGateway->getSql()->select();
        $select ->columns(array('*'));
        
        //getByIds
        if (isset($params['ids']))
            $select->where->in('id', $params['ids']);
        
        if (isset($params['sectionId']))
            $select->where(['sectionId' => $params['sectionId']]);
        
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
    
    public function saveText(\Content\Model\Text $text){
        $data = get_object_vars($text);
        $id = $text->id;
        
        $id = $text->id;
        if ($id == null) 
            return $this->tableGateway->insert($data);
        else//if ($this->isExist($id)) 
            return $this->tableGateway->update($data, array('id' => $id));
        
        return FALSE;
    }
    
    public function deleteText($id) {
        $this->tableGateway->delete(array('id' => $id));
    }
    
    public function saveSorting($sectionId, $sort) {
        $dbAdapter = $this->tableGateway->getAdapter();
        
        $dbAdapter->getDriver()->getConnection()->beginTransaction();
        $sql = new \Zend\Db\Sql\Sql($this->tableGateway->getAdapter());
        $insert = $sql->insert($this->tableGateway->getTable())->columns(array('id' => 'id', 'sectionId' => 'sectionId', 'sort' => 'sort'));
        if (is_array($sort) && count($sort) > 0) {
            foreach ($sort as $ordVal => $id) {
                $insert->values(['id' => $id, 'sectionId' => $sectionId, 'sort' => $ordVal]);
                $insertSql = $sql->getSqlStringForSqlObject($insert)
                    .' ON DUPLICATE KEY UPDATE '
                    .sprintf('%1$s = VALUES(%1$s)', $dbAdapter->getPlatform()->quoteIdentifier('sort'));
                $dbAdapter->query($insertSql, $dbAdapter::QUERY_MODE_EXECUTE);
            }
        }
        $dbAdapter->getDriver()->getConnection()->commit();
        
    }
    
}