<?php
namespace Account\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class Account extends Form {
    
    public function __construct($name = null) {
        parent::__construct('Account');
        $this->setAttribute('method', 'post');
        
        $this->add(array('name' => 'username', 'type' => 'text', 'attributes' => array('placeholder' => 'Название', 'disabled' => 'disabled', 'class' => 'form-control mrg-b-lg'), ));
        $this->add(array('name' => 'email', 'type' => 'text', 'attributes' => array('placeholder' => 'E-mail', 'class' => 'form-control mrg-b-lg'), ));
        
        $this->add(array('name' => 'saveAccount', 'type' => 'submit', 'attributes' => array('value' => 'Сохранить', 'class' => 'btn btn-default'),));
        
        $this->setInputFilter(new \Zend\InputFilter\InputFilter());
    }
    
    
}