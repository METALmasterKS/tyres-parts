<?php
namespace Auth\Form;

use Zend\Form\Form;

class UserRegistration extends Form {

    public function __construct($name = null){
        parent::__construct('registration');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'username',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Логин :'
            )
        ));
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type' => 'password'
            ),
            'options' => array(
                'label' => 'Пароль :'
            )
        ));
        $this->add(array(
            'name' => 'password-confirm',
            'attributes' => array(
                'type' => 'password'
            ),
            'options' => array(
                'label' => 'Подтверждение :'
            )
        ));
        $this->add(array(
            'name' => 'email',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'example@example.com'
            ),
            'options' => array(
                'label' => 'Email :',
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