<?php
namespace Account\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class ChangePassword extends Form {
    
    public function __construct($name = null) {
        parent::__construct('Company');
        $this->setAttribute('method', 'post');
        $this->add(array('name' => 'password-old', 'attributes' => array('type' => 'password'), 'options' => array('label' => 'Текущий пароль :') ));
        $this->add(array('name' => 'password', 'attributes' => array( 'type' => 'password' ), 'options' => array( 'label' => 'Пароль :') ));
        $this->add(array('name' => 'password-confirm', 'attributes' => array('type' => 'password'), 'options' => array('label' => 'Подтверждение :') ));
        
        $this->add(array('name' => 'savePassword', 'type' => 'submit', 'attributes' => array('value' => 'Сохранить', 'class' => 'btn btn-default'),));
        
        $this->setInputFilter(new \Zend\InputFilter\InputFilter());
    }
    
    
}