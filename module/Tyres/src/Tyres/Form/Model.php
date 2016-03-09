<?php
namespace Tyres\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class Model extends Form {
    
    public function __construct($name = null) {
        parent::__construct('Model');
        $this->setAttribute('method', 'post');
        
        $this->add(array('name' => 'name', 'type' => 'text', 'attributes' => array('placeholder' => 'Модель',    'class' => 'form-control mrg-b-lg'), ));
        $this->add(array('name' => 'aliases', 'type' => 'text', 'attributes' => array('placeholder' => 'Алиасы',      'class' => 'form-control mrg-b-lg'), ));
        $this->add(array('name' => 'season', 'type' => 'text', 'attributes' => array('placeholder' => 'Сезонность',      'class' => 'form-control mrg-b-lg'), ));
        
        $this->add(array('name' => 'addModel',    'type' => 'submit', 'attributes' => array('value' => 'Добавить', 'class' => 'btn btn-default'),));
        $this->add(array('name' => 'saveModel',    'type' => 'submit', 'attributes' => array('value' => 'Сохранить', 'class' => 'btn btn-default'),));
        
        $this->setInputFilter(new \Zend\InputFilter\InputFilter());
    }
    
    
}