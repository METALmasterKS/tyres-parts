<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;


use Catalog\Model\NewVariant;

class SearchNewVariantsForm extends Form {
    
    public function __construct($name = null) {
        parent::__construct('SearchNewVariants');
        $this->setAttribute('method', 'get');
        
        $this->add(array('name' => 'name',      'type' => 'text', 'attributes' => array('placeholder' => 'Название',    'class' => 'form-control'), ));
        $this->add(array('name' => 'code_1c',   'type' => 'text', 'attributes' => array('placeholder' => 'Код 1с',      'class' => 'form-control'), ));
        $this->add(array('name' => 'article',   'type' => 'text', 'attributes' => array('placeholder' => 'Артикул',     'class' => 'form-control'), ));
        $this->add(array('name' => 'price_min', 'type' => 'text', 'attributes' => array('placeholder' => 'Цена от',        'class' => 'form-control'), ));
        $this->add(array('name' => 'price_max', 'type' => 'text', 'attributes' => array('placeholder' => 'Цена до',        'class' => 'form-control'), ));
        
        $this->add(array(
            'name' => 'status', 
            'required' => false,
            'type' => 'select', 
            'attributes' => array(
                'class' => 'form-control'
                ), 
            'options' => array(
                'label' => 'Статус',
                'empty_option' => 'Cтатус',
            ),
            'validators' => array(),
            ) );
                
        $this->add(array('name' => 'search',    'type' => 'submit', 'attributes' => array('value' => 'Поиск', 'class' => 'btn btn-default'),));
        
        //$user = new User();
        $this->setInputFilter(new \Zend\InputFilter\InputFilter());//$user->getInputFilter());
        $input = $this->getInputFilter();
        $e_filter = $input->get('status');
        $e_filter->setRequired(false);
    }
    
    
}