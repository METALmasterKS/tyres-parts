<?php
namespace Tyres\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class Model extends Form {
    
    public function __construct($name = null) {
        parent::__construct('Model');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');
        
        $this->add(array('name' => 'name', 'type' => 'text', 
            'options' => ['label' => 'Модель', ], 
            'attributes' => array('placeholder' => 'Модель',    'class' => 'form-control mrg-b-lg'), ));
        $this->add(array('name' => 'aliases', 'type' => 'text', 
            'options' => ['label' => 'Алиасы', ], 
            'attributes' => array('placeholder' => 'Алиасы',      'class' => 'form-control mrg-b-lg'), ));
        $this->add(array('name' => 'season', 'type' => 'text', 
            'options' => ['label' => 'Сезонность', ], 
            'attributes' => array('placeholder' => 'Сезонность',      'class' => 'form-control mrg-b-lg'), ));
        
        $this->add(array(
            'name' => 'imagesfile', 
            'type' => 'file', 
            'options' => [
                'label' => 'Изображения',
                ], 
            'attributes' => array('placeholder' => 'Изображения', 'class' => 'mrg-b-lg', 'multiple' => true,),
            ));
        $this->add(array('name' => 'images', 'type' => 'hidden',));
        
        $this->add(array('name' => 'addModel',    'type' => 'submit', 'attributes' => array('value' => 'Добавить', 'class' => 'btn btn-default'),));
        $this->add(array('name' => 'saveModel',    'type' => 'submit', 'attributes' => array('value' => 'Сохранить', 'class' => 'btn btn-default'),));
        
        $this->setInputFilter(new \Zend\InputFilter\InputFilter());
    }
    
    
}