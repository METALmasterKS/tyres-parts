<?php
namespace Content\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class Section extends Form {
    
    public function __construct($name = null) {
        parent::__construct('Section');
        $this->setAttribute('method', 'post');
        
        $this->add(array('name' => 'name', 'type' => 'text', 'attributes' => array('placeholder' => 'Название',    'class' => 'form-control mrg-b-lg'), ));
        $this->add(array('name' => 'alias', 'type' => 'text', 'attributes' => array('placeholder' => 'Алиас',      'class' => 'form-control mrg-b-lg'), ));
        
        $this->add(array('name' => 'addSection',    'type' => 'submit', 'attributes' => array('value' => 'Добавить', 'class' => 'btn btn-default'),));
        $this->add(array('name' => 'saveSection',    'type' => 'submit', 'attributes' => array('value' => 'Сохранить', 'class' => 'btn btn-default'),));
        
        $this->setInputFilter(new \Zend\InputFilter\InputFilter());
    }
    
    
}