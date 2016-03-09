<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;
//use Zend\InputFilter\InputFilter;

use Auth\Model\User;

class SearchUsersForm extends Form{

    public function __construct($name = null){
        parent::__construct('User');
        $this->setAttribute('method', 'get');
        
        $this->add(array('name' => 'username',          'type' => 'text',   'attributes' => array('placeholder' => 'Логин', 'class' => 'form-control'), ));
        $this->add(array('name' => 'email',             'type' => 'text',   'attributes' => array('placeholder' => 'Email', 'class' => 'form-control'), ));
        $this->add(array('name' => 'phone',             'type' => 'text',   'attributes' => array('placeholder' => 'Телефон', 'class' => 'form-control'), ));
        $this->add(array('name' => 'code_1c',           'type' => 'text',   'attributes' => array('placeholder' => 'Код 1с', 'class' => 'form-control'), ));
        $this->add(array('name' => 'date_register_start',   'type' => 'text',   'attributes' => array('placeholder' => 'Дата регистрации', 'class' => 'form-control'), ));
        $this->add(array('name' => 'date_register_end',     'type' => 'text',   'attributes' => array('placeholder' => 'Дата регистрации', 'class' => 'form-control'), ));
        $this->add(array('name' => 'ip_register',           'type' => 'text',   'attributes' => array('placeholder' => 'IP Регистрации', 'class' => 'form-control'),));
        $this->add(array('name' => 'date_last_login_start', 'type' => 'text',   'attributes' => array('placeholder' => 'Дата последнего входа', 'class' => 'form-control'),));
        $this->add(array('name' => 'date_last_login_end',   'type' => 'text',   'attributes' => array('placeholder' => 'Дата последнего входа', 'class' => 'form-control'),));
        $this->add(array('name' => 'ip_last_login',     'type' => 'text',   'attributes' => array('placeholder' => 'IP Входа', 'class' => 'form-control'),));
        $this->add(array('name' => 'search',            'type'  => 'submit','attributes' => array('value' => 'Поиск', 'class' => 'btn btn-default'),));
        
        //$user = new User();
        $this->setInputFilter(new \Zend\InputFilter\InputFilter());//$user->getInputFilter());
    }
    
    
}