<?php

namespace Auth\Form;

use Zend\Form\Form;

class UserLogin extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('login');
        $this->setAttribute('method', 'post');
        $this->add(array('name' => 'username', 'attributes' => array('type' => 'text'), 'options' => array('label' => 'Логин')));
        $this->add(array('name' => 'password', 'attributes' => array('type' => 'password'), 'options' => array('label' => 'Пароль')));
        $this->add(array('name' => 'submit', 'attributes' => array('type' => 'submit', 'value' => 'Войти',)));
        
        $this->setInputFilter(new \Zend\InputFilter\InputFilter());
    }

}
