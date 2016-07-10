<?php
namespace Auth\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class User extends Form {
    
    public function __construct($name = null) {
        parent::__construct('User');
        $this->setAttribute('method', 'post');
        
        $this->add(array('name' => 'id', 'type' => 'hidden', ));
        
        $this->add(array('name' => 'email', 'type' => 'text', 
            'options' => ['label' => 'Email', ], 
            'attributes' => array('placeholder' => 'Email', 'readonly' => 'readonly', 'class' => 'form-control mrg-b-lg'), ));
        $this->add(array('name' => 'phone', 'type' => 'text', 
            'options' => ['label' => 'Телефон', ], 
            'attributes' => array('placeholder' => 'Телефон', 'readonly' => 'readonly', 'class' => 'form-control mrg-b-lg'), ));
        $this->add(array('name' => 'discount', 'type' => 'text',
            'options' => ['label' => 'Скидка, %', ], 
            'attributes' => array('placeholder' => 'Скидка',      'class' => 'form-control mrg-b-lg'), ));
        
        $this->add(array('name' => 'addUser',    'type' => 'submit', 'attributes' => array('value' => 'Добавить', 'class' => 'btn btn-default'),));
        $this->add(array('name' => 'saveUser',    'type' => 'submit', 'attributes' => array('value' => 'Сохранить', 'class' => 'btn btn-default'),));
        
        $this->setInputFilter(new \Zend\InputFilter\InputFilter());
    }
    
    
}