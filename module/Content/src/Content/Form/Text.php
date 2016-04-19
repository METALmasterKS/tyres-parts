<?php
namespace Content\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class Text extends Form {
    
    public function __construct($name = null) {
        parent::__construct('Text');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');
        
        $this->add(array('name' => 'name', 'type' => 'text', 
            'options' => ['label' => 'Заголовок', ], 
            'attributes' => array('placeholder' => 'Заголовок',    'class' => 'form-control mrg-b-lg'), ));
        
        $this->add(array('name' => 'alias', 'type' => 'text', 'attributes' => array('placeholder' => 'Алиас',      'class' => 'form-control mrg-b-lg'), ));
        
        $this->add(array('name' => 'text', 'type' => 'textarea', 
            'options' => ['label' => 'Содержание', ], 
            'attributes' => array('placeholder' => 'Содержание',      'class' => 'form-control mrg-b-lg', 'id'=>'ckeditor'), ));
        
        $this->add(array('name' => 'image', 'type' => 'text', 
            'attributes' => array('placeholder' => 'Ссылка на изображение', 'class' => 'form-control mrg-t-lg'), 
            'options' => ['description' => 'Рекомендуется изображение шириной 820px и высотой 390px', ],
        ));
        
        $this->add(array('name' => 'addText',    'type' => 'submit', 'attributes' => array('value' => 'Добавить', 'class' => 'btn btn-default'),));
        $this->add(array('name' => 'saveText',    'type' => 'submit', 'attributes' => array('value' => 'Сохранить', 'class' => 'btn btn-default'),));
        
        $this->setInputFilter(new \Zend\InputFilter\InputFilter());
    }
    
    
}