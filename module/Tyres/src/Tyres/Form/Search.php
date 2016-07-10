<?php

namespace Tyres\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class Search extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Search');
        $this->setAttribute('method', 'get');
        $this->setAttribute('class', 'type1-form');
        $this->setAttribute('id', 'form_565');

        $this->add(array(
            'name' => 'brandName', 
            'type' => 'select', 
            'options' => [
                'label' => 'Бренд',
                'empty_option' => '',
                'disable_inarray_validator' => true,
                ],
            'attributes' => array(
                'placeholder' => 'Бренд', 
                'class' => 'js-ajax-selectbrand'
                ),
            ));
        
        $this->add(array(
            'name' => 'modelName', 
            'type' => 'select', 
            'options' => [
                'label' => 'Модель',
                'empty_option' => '',
                ],
            'attributes' => array(
                'placeholder' => 'Модель', 
                'class' => 'js-ajax-selectmodel'
                ),
            ));
        
        $this->add(array(
            'name' => 'width', 
            'type' => 'select', 
            'options' => [
                'label' => 'Размер',
                'empty_option' => '',
                ],
            'attributes' => array(
                'placeholder' => 'Ширина', 
                'class' => ''
                ),
            ));
        
        $this->add(array(
            'name' => 'height', 
            'type' => 'select', 
            'options' => [
                'label' => '<span class="input-group-addon">/</span>',
                'empty_option' => '',
                ], 
            'attributes' => array(
                'placeholder' => 'Профиль', 
                'class' => ''
                ),
            ));
        
        $this->add(array(
            'name' => 'diameter', 
            'type' => 'select', 
            'options' => [
                'label' => '<span class="input-group-addon">R</span>',
                'empty_option' => '',
                ], 
            'attributes' => array(
                'placeholder' => 'Диаметр', 
                'class' => ''
                ),
            ));
        
        $this->add(array(
            'name' => 'season', 
            'type' => 'radio', 
            'options' => [
                'label' => 'Сезон',
                'empty_option' => '',
                'value_options' => array(
                        'summer' => 'Летние',
                        'winter' => 'Зимние',
                        'allseason' => 'Всесезонные',
                    ),
                'label_attributes' => array(
                    'class' => 'one_third'
                ),
            ], 
            'attributes' => array(
                'placeholder' => 'Сезон', 
                
                ),
            ));
        
        
        $this->add(array('name' => 'search', 'type' => 'submit', 'attributes' => array('value' => 'Поиск', 'class' => 'cmsms_button'),));
        
        $this->setInputFilter(new \Zend\InputFilter\InputFilter());
        
        $inputFilter = $this->getInputFilter();
        
        $inputFilter->get('brandName')->setRequired(false);
        $inputFilter->get('modelName')->setRequired(false);
        $inputFilter->get('width')->setRequired(false);
        $inputFilter->get('height')->setRequired(false);
        $inputFilter->get('diameter')->setRequired(false);
        $inputFilter->get('season')->setRequired(false);
        

        
        
    }

}
