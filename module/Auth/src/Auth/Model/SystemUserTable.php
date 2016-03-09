<?php
namespace Auth\Model;
 
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
 
class SystemUserTable extends TableGateway
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
 
    public function getUser($id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'))
            ->where(['sys_users.id = ?' => (int) $id])
            ;
        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();
        
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function getUserByEmail($email)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'))
            ->where(['sys_users.email = ?' => (string) $email])
            ;
        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find email $email");
        }
        return $row;
    }
 
    public function saveUser(SystemUser $user)
    {
        $data = get_object_vars($user);
        unset($data['id']);
        $id = (int)$user->id;
        if ($id == null) 
            return $this->tableGateway->insert($data);
        else//if ($this->isExist($id)) 
            return $this->tableGateway->update($data, array('id' => $id));
        
        return FALSE;
    }
 
    public function deleteUser($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
    public function getUsers($params = array())
    {
        //основной запрос
        $select = $this->tableGateway->getSql()->select();
        $select ->columns(array('*'));
        
        //email
        if (isset($params['email']))
            $select->where->like('sys_users.email', $params['email']);

        //дата регистр ОТ
        if (isset($params['date_register_start']))
            $select->where->greaterThanOrEqualTo('sys_users.date_register', $params['date_register_start']);
        //дата регистр ДО
        if (isset($params['date_register_end']))
            $select->where->lessThanOrEqualTo('sys_users.date_register', $params['date_register_end']);

        //дата последнего входа ОТ
        if (isset($params['date_last_login_start']))
            $select->where->greaterThanOrEqualTo('sys_users.date_last_login', $params['date_last_login_start']);
        //дата последнего входа ДО
        if (isset($params['date_last_login_end']))
            $select->where->lessThanOrEqualTo('sys_users.date_last_login', $params['date_last_login_end']);
        
        //порядок
        if (isset($params['order']) && isset($converter[$params['order']]) && isset($params['way']))
            $select->order('sys_users.'.$params['order'].' '.(isset($params['way']) ? ( ((bool) $params['way']) ? 'ASC':'DESC'):'DESC'));
        //getUsersByIds
        if (isset($params['ids']))
            $select->where->in('sys_users.id', $params['ids']);
        //pagination flag
        if (isset($params['pagination']) && (bool)$params['pagination']){
            $paginator = new Paginator(new DbSelect($select, $this->tableGateway->getAdapter(), $this->tableGateway->getResultSetPrototype()));
            return $paginator;
        } else {
            $resultSet = $this->tableGateway->selectWith($select);
            return $resultSet;
        }
    }
    
}