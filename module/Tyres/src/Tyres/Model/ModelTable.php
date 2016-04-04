<?php
namespace Tyres\Model;
 
use Zend\Db\TableGateway\TableGateway as TableGateway;

use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
 
class ModelTable extends TableGateway
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
    
    public function getModel($id)
    {
        $id  = (int) $id;
        $resultSet = $this->tableGateway->select(['id' => $id]);
        return $resultSet->current();
    }
    
    public function getModels($params = array())
    {
        //основной запрос
        $select = $this->tableGateway->getSql()->select();
        $select ->columns(array('*'));
        
        if (isset($params['name']))
            $select->where->like('name', $params['name']);

        if (isset($params['aliases']))
            $select->where->like('aliases', $params['aliases']);
        
        if (isset($params['season']))
            $select->where->like('season', $params['season']);
        
        if (isset($params['brandId']))
            $select->where->equalTo('brandId', $params['brandId']);
        
        if (isset($params['images']))
            $select->where->like('images', $params['images']);
        
        //getByIds
        if (isset($params['ids']))
            $select->where->in('id', $params['ids']);
        
        //порядок
        if (isset($params['order']))
            $select->order($params['order'].' '.(isset($params['way']) ? ( ((bool) $params['way']) ? 'ASC':'DESC'):'ASC'));
        
        //pagination flag
        if (isset($params['pagination']) && (bool) $params['pagination']) {
            return new Paginator(new DbSelect($select, $this->tableGateway->getAdapter(), $this->tableGateway->getResultSetPrototype()));
        } else {
            $resultSet = $this->tableGateway->selectWith($select);
            $resultSet->buffer();
            return $resultSet;
        }
    }
    
    public function saveModel(\Tyres\Model\Model $model){
        $data = get_object_vars($model);
        $id = $model->id;
        
        if ($id == null) 
            return $this->tableGateway->insert($data);
        else//if ($this->isExist($id)) 
            return $this->tableGateway->update($data, array('id' => $id));
        
        return FALSE;
    }
    
    public function deleteModel($ids)
    {
        $this->tableGateway->delete(array('id' => $ids));
    }
}