<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class Feedback extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Feedback');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'wpcf7-form'); //type1-form
        //$this->setAttribute('id', 'form_565');

        $this->add(array(
            'name' => 'theme', 
            'type' => 'select', 
            'options' => [
                'label' => 'Тема',
                'empty_option' => 'Выберите тему сообщения',
                'value_options' => [
                    [ 'value' => 'Шины, диски', 'label' => 'Шины, диски', ],
                    [ 'value' => 'Запчасти', 'label' => 'Запчасти', ],
                    [ 'value' => 'Сервис', 'label' => 'Сервис', ],
                    [ 'value' => 'Дополнительное оборудование', 'label' => 'Дополнительное оборудование', ],
                    [ 'value' => 'Мойка', 'label' => 'Мойка', ],
                    [ 'value' => 'Организационный вопрос', 'label' => 'Организационный вопрос', ],
                ],
            ],
            'attributes' => array(
                'class' => 'js-feedback-theme'
            ),
        ));
        
        $this->add(array(
            'name' => 'car-brand', 
            'type' => 'text', 
            'options' => [
                'label' => 'Марка автомобиля',
                ],
            'attributes' => array(
                ),
            ));
        
        $this->add(array(
            'name' => 'car-model', 
            'type' => 'text', 
            'options' => [
                'label' => 'Модель автомобиля',
                ],
            'attributes' => array(
                ),
            ));
        
        $this->add(array(
            'name' => 'car-year', 
            'type' => 'text', 
            'options' => [
                'label' => 'Год выпуска автомобиля',
                ],
            'attributes' => array(
                ),
            ));
        
        $this->add(array(
            'name' => 'car-vin', 
            'type' => 'text', 
            'options' => [
                'label' => 'VIN Номер автомобиля',
                ],
            'attributes' => array(
                ),
            ));
        
        $this->add(array(
            'name' => 'message', 
            'type' => 'textarea', 
            'options' => [
                'label' => 'Сообщение',
                ],
            'attributes' => array(
                ),
            ));
        
        $this->add(array(
            'name' => 'name', 
            'type' => 'text', 
            'options' => [
                'label' => 'Фамилия Имя',
                ],
            'attributes' => array(
                ),
            ));
        
        $this->add(array(
            'name' => 'email',
            'type' => 'text',
            'attributes' => array(
                //'placeholder' => 'example@example.com'
            ),
            'options' => array(
                'label' => 'Электронная почта',
            )
        ));
        
        $this->add(array(
            'name' => 'phone',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => '+7 921 123 45 67'
            ),
            'options' => array(
                'label' => 'Телефон',
            )
        ));
        
        
        $this->add(array('name' => 'feedback', 'type' => 'submit', 'attributes' => array(
            'value' => 'Отправить', 'class' => 'wpcf7-form-control wpcf7-submit',
            ),));
        
        
        
        
        $inputFilter = new \Zend\InputFilter\InputFilter();
        
        $inputFilter->add(array( 'name' => 'name', 'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
            ),
            'validators' => array(
            ),
        ));
        
        $inputFilter->add(array( 'name' => 'message', 'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
            ),
            'validators' => array(
            ),
        ));
        
        $inputFilter->add(array('name' => 'email', 'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
            ),
            'validators' => array(
                array('name' => 'EmailAddress', ),
            ),
        ));
        
        $inputFilter->add(array( 'name' => 'phone', 'required' => false,
            'filters' => array(
                array('name' => 'StringTrim'),
                array('name' => 'StripTags'),
            ),
            'validators' => array(
                new \Zend\I18n\Validator\PhoneNumber(['country'=>'RU', 'allowed_types' => array('general', 'fixed', 'mobile'), 'allow_possible' => true])
            )
        ));
        
        $this->setInputFilter($inputFilter);
        
    }

}
