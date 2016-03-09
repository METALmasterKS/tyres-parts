<?php

namespace Auth\Form;

use Zend\Form\Form;

class UserResetPassword extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('resetpassword');
        $this->setAttribute('method', 'post');
        $this->add(array('name' => 'username', 'attributes' => array('type' => 'text'), 'options' => array('label' => 'Логин')));
        $this->add(array('name' => 'email', 'type' => 'text', 'attributes' => array('placeholder' => 'example@example.com'), 'options' => array('label' => 'Email',)));
        $this->add(array('name' => 'submit', 'attributes' => array('type' => 'submit', 'value' => 'Востановить',)));
        
        $inputFilter = new \Zend\InputFilter\InputFilter();
        
        $inputFilter->add(array( 'name' => 'username', 'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
            ),
            'validators' => array(
            ),
        ));
        
        $inputFilter->add(array('name' => 'email', 'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
            ),
            'validators' => array(
                array('name' => 'EmailAddress', ),
            ),
        ));
        
        $this->setInputFilter($inputFilter);
    }
    
    

}
