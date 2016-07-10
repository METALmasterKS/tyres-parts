<?php

namespace Auth\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class UserTable {
	
	protected $tableGateway;
	
	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}
    
    public function fetchAll() {
        if (true) {
            return new Paginator(new DbSelect(new Select($this->tableGateway->getTable()), $this->tableGateway->getAdapter(),$this->tableGateway->getResultSetPrototype()));
        }
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    public function getUsers($params = array())
    {
        //основной запрос
        $select = $this->tableGateway->getSql()->select();
        $select ->columns(array('*'));
        //login
        if (isset($params['username']))
            $select->where->like('users.username', $params['username']);
        //email
        if (isset($params['email']))
            $select->where->like('users.email', $params['email']);
        //phone
        if (isset($params['phone']))
            $select->where->like('users.phone', $params['phone']);
        //discount
        if (isset($params['discount']))
            $select->where->equalTo('users.discount', $params['discount']);
        //code 1c
        if (isset($params['code_1c']))
            $select->where->like('users.code_1c', $params['code_1c']);
        //дата регистр ОТ
        if (isset($params['date_register_start']))
            $select->where->greaterThanOrEqualTo('users.date_register', $params['date_register_start']);
        //дата регистр ДО
        if (isset($params['date_register_end']))
            $select->where->lessThanOrEqualTo('users.date_register', $params['date_register_end']);
        //ip регистрации
        if (isset($params['ip_register']))
            $select->where->like('users.ip_register', $params['ip_register']);
        //дата последнего входа ОТ
        if (isset($params['date_last_login_start']))
            $select->where->greaterThanOrEqualTo('users.date_last_login', $params['date_last_login_start']);
        //дата последнего входа ДО
        if (isset($params['date_last_login_end']))
            $select->where->lessThanOrEqualTo('users.date_last_login', $params['date_last_login_end']);
        //ip последнего входа
        if (isset($params['ip_last_login']))
            $select->where->like('users.ip_last_login', $params['ip_last_login']);
        
        
        //порядок
        if (isset($params['order']) && isset($converter[$params['order']]) && isset($params['way']))
            $select->order('users.'.$params['order'].' '.(isset($params['way']) ? ( ((bool) $params['way']) ? 'ASC':'DESC'):'DESC'));
        //getUsersByIds
        if (isset($params['ids']))
            $select->where->in('users.id', $params['ids']);
        //pagination flag
        if (isset($params['pagination']) && (bool)$params['pagination']){
            $paginator = new Paginator(new DbSelect($select, $this->tableGateway->getAdapter(), $this->tableGateway->getResultSetPrototype()));
            return $paginator;
        } else {
            $resultSet = $this->tableGateway->selectWith($select);
            return $resultSet;
        }
    }
	
	public function getUser($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array(
				'id' => $id
		));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function getUserByEmail($mail) {
	    $rowset = $this->tableGateway->select(array(
	    		'email' => $mail
	    ));
	    $row = $rowset->current();
	    
	    return $row;
	}
    
    public function saveUser(\Auth\Model\User $user){
        $id = $user->id;
        $data = get_object_vars($user);

        if ($id == null) 
            return $this->tableGateway->insert($data);
        else//if ($this->isExist($id)) 
            return $this->tableGateway->update($data, array('id' => $id));
        
        return FALSE;
    }
    
    public function deleteUser($ids)
    {
        return $this->tableGateway->delete(array('id' => $ids));
    }
    
}