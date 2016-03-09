<?php
namespace Tyres\Model;
 
use Zend\Db\TableGateway\TableGateway as TableGateway;

use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
 
class BrandTable extends TableGateway
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
    
    public function getBrand($id)
    {
        $id  = (int) $id;
        $resultSet = $this->tableGateway->select(['id' => $id]);
        return $resultSet->current();
    }
    
    public function getBrands($params = array())
    {
        //основной запрос
        $select = $this->tableGateway->getSql()->select();
        $select ->columns(array('*'));
        
        if (isset($params['name']))
            $select->where->like('name', $params['name']);
        if (isset($params['aliases']))
            $select->where->like('aliases', $params['aliases']);
        //getByIds
        if (isset($params['ids']))
            $select->where->in('id', $params['ids']);
        
        // количество шин в бренде
        if (isset($params['tyresCountLoad'])) {
            $selectIdCount = new \Zend\Db\Sql\Select();
            $selectIdCount->from(array('tm' => 'tyres_models'))
                ->join(array('t' => 'tyres'), 't.modelId = tm.id', array(), 'LEFT')
                ->columns(['id' => 'brandId', 'tyresCount' => new \Zend\Db\Sql\Expression('count(t.id)')])
                ->group('tm.brandId');
            
            $select->join(array('tc' => $selectIdCount), 'tc.id = tyres_brands.id', array('tyresCount'), 'LEFT');
        }
        
        //порядок
        if (isset($params['order']))
            $select->order($params['order'].' '.(isset($params['way']) ? (boolval($params['way']) ? 'ASC':'DESC'):'ASC'));
        
        //pagination flag
        if (isset($params['pagination']) && (bool) $params['pagination']) {
            return new Paginator(new DbSelect($select, $this->tableGateway->getAdapter(), $this->tableGateway->getResultSetPrototype()));
        } else {
            $resultSet = $this->tableGateway->selectWith($select);
            $resultSet->buffer();
            return $resultSet;
        }
    }
    
    public function saveBrand(\Tyres\Model\Brand $brand){
        $data = get_object_vars($brand);
            /*array(
            'name' => $brand->name,
        );*/
        $id = $brand->id;
        
        if ($id == null) 
            return $this->tableGateway->insert($data);
        else//if ($this->isExist($id)) 
            return $this->tableGateway->update($data, array('id' => $id));
        
        return FALSE;
    }
    
    public function deleteBrand($ids)
    {
        $this->tableGateway->delete(array('id' => $ids));
    }
}