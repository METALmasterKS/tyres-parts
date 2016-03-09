<?php
namespace Tyres\Model;
 
use Zend\Db\TableGateway\TableGateway as TableGateway;
 
class TyreTable extends TableGateway
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
 
    public function getTyre($id) {
        $id  = (int) $id;
        $resultSet = $this->tableGateway->select(['id' => $id]);
        return $resultSet->current();
    }
    
    public function getTyres($params = null) 
    {
        //основной запрос
        $select = $this->tableGateway->getSql()->select();
        $select ->columns(array('*'));
        
        //getByIds
        if (isset($params['ids']))
            $select->where->in('id', $params['ids']);
        
        if (isset($params['width']))
            $select->where->equalTo('width', $params['width']);
        if (isset($params['height']))
            $select->where->equalTo('height', $params['height']);
        if (isset($params['diameter']))
            $select->where->equalTo('diameter', $params['diameter']);
        
        if (isset($params['brandId']) || isset($params['brandName']) || isset($params['season'])){
            $select
                ->join(array('m' => 'tyres_models'), 'm.id = tyres.modelId', array(), 'LEFT')
                ->join(array('b' => 'tyres_brands'), 'b.id = m.brandId', array(), 'LEFT');
            if (isset($params['brandId']))
                $select->where(['b.id' => $params['brandId']]);
            if (isset($params['brandName']))
                $select->where(['b.name' => $params['brandName']]);
            if (isset($params['modelId']))
                $select->where(['m.id' => $params['modelId']]);
            if (isset($params['modelName']))
                $select->where(['m.name' => $params['modelName']]);
            if (isset($params['season']))
                $select->where(['m.season' => $params['season']]);
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
    
    public function saveTyre(\Catalog\Model\Tyre $tyre){
        $data = $tyre->getArrayCopy();

        $id = $tyre->id;
        if ($id == null) 
            return $this->tableGateway->insert($data);
        elseif ($this->isExist($id)) 
            return $this->tableGateway->update($data, array('id' => $id));
        
        return FALSE;
    }
    
    public function deleteTyre($id) {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
    
    public function getOneProperty($name, $where = null) {
        $select = $this->tableGateway->getSql()->select();
        $select ->columns(array($name))->quantifier(\Zend\Db\Sql\Select::QUANTIFIER_DISTINCT);
        if (isset($where))
            $select->where($where);
        
        $select->order($name." ASC");
        
        $props = [];
        $resultSet = $this->tableGateway->selectWith($select);
        foreach ($resultSet as $row)
            $props[] = $row->{$name};
        return $props;
    }

    
    
}