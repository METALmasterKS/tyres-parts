<?php
namespace Auth\Form;

use Zend\Form\Form;

class UserRegistration extends Form {

    public function __construct($name = null){
        parent::__construct('registration');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'type1-form');
        $this->add(array(
            'name' => 'email',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'example@example.com'
            ),
            'options' => array(
                'label' => 'Email',
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
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type' => 'password'
            ),
            'options' => array(
                'label' => 'Пароль'
            )
        ));
        $this->add(array(
            'name' => 'password-confirm',
            'attributes' => array(
                'type' => 'password'
            ),
            'options' => array(
                'label' => 'Подтверждение'
            )
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Зарегистрироваться',
            )
        ));
        
        $this->setInputFilter(new \Zend\InputFilter\InputFilter());
    }
}