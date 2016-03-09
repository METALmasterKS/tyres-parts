<?php
namespace Tyres\Model;
 
use Zend\Db\TableGateway\TableGateway as TableGateway;

 
class PriceTable extends TableGateway
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
    
    public function getPrice($id)
    {
        $id  = (int) $id;
        $resultSet = $this->tableGateway->select(['id' => $id]);
        return $resultSet->current();
    }
    
    public function getPrices($params = array())
    {
        //основной запрос
        $select = $this->tableGateway->getSql()->select();
        $select ->columns(array('*'));
        
        if (isset($params['providerId']))
            $select->where->equalTo('providerId', $params['providerId']);
        
        if (isset($params['tyreIds']))
            $select->where->in('tyreId', $params['tyreIds']);
        
        //getByIds
        if (isset($params['ids']))
            $select->where->in('id', $params['ids']);
        
        //порядок
        if (isset($params['order']))
            $select->order($params['order'].' '.(isset($params['way']) ? (boolval($params['way']) ? 'ASC':'DESC'):'ASC'));

        $resultSet = $this->tableGateway->selectWith($select);
        $resultSet->buffer();
        return $resultSet;
    }
    
    public function savePrice(\Tyres\Model\Price $model){
        $data = $model->getArrayCopy();
        $id = $model->id;
        
        if ($id == null) 
            return $this->tableGateway->insert($data);
        else//if ($this->isExist($id)) 
            return $this->tableGateway->update($data, array('id' => $id));
        
        return FALSE;
    }
    
    public function deletePrice($ids)
    {
        $this->tableGateway->delete(array('id' => $ids));
    }
}