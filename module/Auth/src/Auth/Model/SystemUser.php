<?php
namespace Auth\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
 
class SystemUser implements InputFilterAwareInterface
{
    public $id;
    public $email;
    public $password;
    public $name;
    
    private $serviceLocator; //serviceManager
    
    protected $inputFilter;  

    public function __construct($serviceLocator = null)
    {
        $this->serviceLocator = $serviceLocator; 
    }
 
    public function exchangeArray($data)
    {
        $this->id           = isset($data['id']) ? (int) $data['id'] : null;
        $this->email        = isset($data['email']) ? $data['email'] : null;
        $this->password     = isset($data['password']) ? $data['password'] : null;
        $this->name       = isset($data['name']) ? $data['name'] : '';
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter($action = 'registration')
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            
            $inputFilter->add(array('name' => 'id', 'required' => false, 'filters' => array( array('name' => 'Int'), )));
            
            $inputFilter->add(array( 'name' => 'name', 'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                ),
                'validators' => array(
                    array('name' => 'NotEmpty', 'break_chain_on_failure' => true, 'options' => array(
                            'messages' => array( \Zend\Validator\NotEmpty::IS_EMPTY => 'Введите ваше Имя.' )
                        ),
                    ),
                    array('name' => 'StringLength', 'break_chain_on_failure' => true, 'options' => array( 'encoding' => 'UTF-8', 'min' => 2, 'max' => 64, ), ),
                    array('name' => 'Regex', 'options' => array('pattern' => '/^[a-zа-яё\-]+$/iu', ), ),
                ),
            ));
            
            $inputFilter->add(array( 'name' => 'email', 'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                ),
                'validators' => array(
                    array('name' => 'NotEmpty', 'break_chain_on_failure' => true, 'options' => array(
                            //'messages' => array( \Zend\Validator\NotEmpty::IS_EMPTY => 'Email должен быть заполнен.' )
                        ),
                    ),
                    array('name' => 'EmailAddress', 'options' => array(
                            //'messages' => array( \Zend\Validator\EmailAddress::INVALID_FORMAT => 'Проверьте правильность ввода адреса электронной почты.' )
                        ),
                    ),
                    array('name' => 'Regex', 'options' => array(
                        'pattern' => '/@3259404\.ru$/i', 
                        'messages' => array( \Zend\Validator\Regex::NOT_MATCH => 'Почта должна быть на домене 3259404.ru.' )
                        ), 
                    ),
                ),
            ));
            
            $inputFilter->add(array( 'name' => 'password', 'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array('name' => 'NotEmpty', 'break_chain_on_failure' => true, 'options' => array(
                            //'messages' => array( \Zend\Validator\NotEmpty::IS_EMPTY => 'Поле "Пароль" должено быть заполнено.' )
                        ),
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array('min' => 6, 'max' => 64,
                            /*'messages' => array(
                                \Zend\Validator\StringLength::INVALID => 'Длина пароля должена быть больше %min%, но меньше %max% символов.',
                                \Zend\Validator\StringLength::TOO_LONG => 'Длина пароля должена быть меньше %max% символов.',
                                \Zend\Validator\StringLength::TOO_SHORT => 'Длина пароля должена быть больше %min% символов.',
                            )*/
                        ),
                    ),
                )
            ));

            $inputFilter->add(array( 'name' => 'password-confirm', 'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array('name'    => 'Identical', 'options' => array( 'token' => 'password',
                            /*'messages' => array(
                                \Zend\Validator\Identical::NOT_SAME => 'Поля "Подтверждение" и "Пароль" должены быть идентичными.', 
                                \Zend\Validator\Identical::MISSING_TOKEN => 'Подтвердите пароль.', 
                            )*/
                        ),
                    ),
                )
            ));
            
            $inputFilter->add(array( 'name' => 'password-old', 'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array('name' => 'NotEmpty', 'break_chain_on_failure' => true, 'options' => array(), ),
                )
            ));
            
            
            if (isset($this->serviceLocator)) {
                $inputFilter->get('email')->getValidatorChain()->addByName('Db\NoRecordExists', array(
                    'table'   => 'sys_users',
                    'field'   => 'email',
                    'exclude' => $this->id != null ? array('field' => 'id', 'value' => $this->id) : null,
                    'adapter' => $this->serviceLocator->get('Zend\Db\Adapter\Adapter'),
                    'messages' => array( 
                        \Zend\Validator\Db\AbstractDb::ERROR_RECORD_FOUND => 'Существует аккаунт с данным адресом электронной почты, попробуйте восстановить пароль.', 
                    ),    
                ));
            }
            
            if ($action == 'registration') {
                
            } elseif ($action == 'edit-user') {
                
            } elseif ($action == 'change-password') {
                $inputFilter->get('password-old')->setRequired(true);
                $inputFilter->get('username')->setRequired(false);
                $inputFilter->get('email')->setRequired(false);
            }
            
                        
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }
    
}