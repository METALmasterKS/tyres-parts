<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class SearchBrandsForm extends Form {
    
    public function __construct($name = null) {
        parent::__construct('Brand');
        $this->setAttribute('method', 'get');
        
        $this->add(array('name' => 'name', 'type' => 'text', 'attributes' => array('placeholder' => 'Бренд',    'class' => 'form-control mrg-b-lg'), ));
        $this->add(array('name' => 'aliases', 'type' => 'text', 'attributes' => array('placeholder' => 'Алиасы',      'class' => 'form-control mrg-b-lg'), ));
        
        $this->add(array('name' => 'search', 'type'  => 'submit','attributes' => array('value' => 'Поиск', 'class' => 'btn btn-default mrg-b-lg'),));
        
        $this->setInputFilter(new \Zend\InputFilter\InputFilter());
    }
    
    
}