<?php

namespace Auth\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class User implements InputFilterAwareInterface
{

    public $id;
    public $username;
    public $password;
    public $parent_id;
    public $email;
    public $phone;
    public $code_1c;
    public $is_bonus;
    public $points;
    public $chance_points;
    public $date_register;
    public $ip_register;
    public $date_last_login;
    public $ip_last_login;
    
    private $serviceLocator; //serviceManager
    
    protected $inputFilter;  

    public function __construct($serviceLocator = null)
    {
        $this->serviceLocator = $serviceLocator; 
    }
    
    public function __sleep()
    {
        return array_diff(array_keys(get_object_vars($this)), array('serviceLocator', 'inputFilter'));
    }
    
    function __wakeup()
    {
        // set serviceLocator again on wakeup!
    }

    public function exchangeArray($data)
    {
        $this->id                   = (isset($data['id']))                  ? (int) $data['id'] : null;
        $this->username             = (isset($data['username']))            ?       $data['username'] : null;
        $this->password             = (isset($data['password']))            ?       $data['password'] : null;
        $this->parent_id            = (isset($data['parent_id']))           ? (int) $data['parent_id'] : '';
        $this->email                = (isset($data['email']))               ?       $data['email'] : '';
        $this->phone                = (isset($data['phone']))               ?       $data['phone'] : '';
        $this->code_1c              = (isset($data['code_1c']))             ?       $data['code_1c'] : '';
        $this->is_bonus             = (isset($data['is_bonus']))            ?       $data['is_bonus'] == 1 : 0;
        $this->points               = (isset($data['points']))              ? (int) $data['points'] : 0;
        $this->chance_points        = (isset($data['chance_points']))       ? (int) $data['chance_points'] : 0;
        $this->date_register        = (isset($data['date_register']))       ? (int) $data['date_register'] : 0;
        $this->ip_register          = (isset($data['ip_register']))         ?       $data['ip_register'] : '';
        $this->date_last_login      = (isset($data['date_last_login']))     ? (int) $data['date_last_login'] : 0;
        $this->ip_last_login        = (isset($data['ip_last_login']))       ?       $data['ip_last_login'] : '';
                
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
            $inputFilter->add(array('name' => 'parent_id', 'required' => false, 'filters' => array( array('name' => 'Int'), )));
            
            $inputFilter->add(array( 'name' => 'username', 'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                ),
                'validators' => array(
                    array('name' => 'NotEmpty', 'break_chain_on_failure' => true, 'options' => array(
                            'messages' => array( \Zend\Validator\NotEmpty::IS_EMPTY => 'Логин должен быть заполнен.' )
                        ),
                    ),
                    array('name' => 'StringLength', 'break_chain_on_failure' => true, 'options' => array( 'encoding' => 'UTF-8', 'min' => 4, 'max' => 64,
                            /*'messages' => array(
                                \Zend\Validator\StringLength::INVALID => 'Длина логина должен быть больше %min%, но меньше %max% символов.',
                                \Zend\Validator\StringLength::TOO_LONG => 'Длина логина должен быть меньше %max% символов.',
                                \Zend\Validator\StringLength::TOO_SHORT => 'Длина логина должен быть больше %min% символов.',
                            ),*/
                        ),
                    ),
                    array('name' => 'Regex', 'options' => array(
                            'pattern' => '/^(?=.{4,64}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$/',
                            //"messages" => array( \Zend\Validator\Regex::NOT_MATCH => "Проверьте правильность ввода", )
                        ),
                    ),
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
            
            $inputFilter->add(array( 'name' => 'phone', 'required' => true,
                'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
                'validators' => array(
                    new \Zend\I18n\Validator\PhoneNumber(['country'=>'RU', 'allowed_types' => array('general', 'fixed', 'mobile'), 'allow_possible' => true])
                )
            ));
            
            $inputFilter->add(array('name' => 'ip_register', 'required' => false,
                'filters' => array(
                    array('name' => 'StringTrim')
                ),
                'validators' => array(
                    new \Zend\Validator\Ip()
                )
            ));
            
            if (isset($this->serviceLocator)) {
                $inputFilter->get('username')->getValidatorChain()->addByName('Db\NoRecordExists', array(
                    'table'   => 'users',
                    'field'   => 'username',
                    'exclude' => $this->id != null ? array('field' => 'id', 'value' => $this->id) : null,
                    'adapter' => $this->serviceLocator->get('DBMaster'),
                    'messages' => array( 
                        \Zend\Validator\Db\AbstractDb::ERROR_RECORD_FOUND => 'Существует пользователь с данным логином, попробуйте ввести другой логин или восстановите пароль, если это ваш логин.', 
                    ),
                ));
                $inputFilter->get('email')->getValidatorChain()->addByName('Db\NoRecordExists', array(
                    'table'   => 'users',
                    'field'   => 'email',
                    'exclude' => $this->id != null ? array('field' => 'id', 'value' => $this->id) : null,
                    'adapter' => $this->serviceLocator->get('DBMaster'),
                    'messages' => array( 
                        \Zend\Validator\Db\AbstractDb::ERROR_RECORD_FOUND => 'Существует аккаунт с данным адресом электронной почты, попробуйте восстановить пароль.', 
                    ),    
                ));
            }
            
            if ($action == 'registration') {
                $inputFilter->get('phone')->setRequired(false);
            } elseif ($action == 'edit-user') {
                $inputFilter->get('username')->setRequired(false);
                $inputFilter->get('password')->setRequired(false);
                $inputFilter->get('password-confirm')->setRequired(false);
                $inputFilter->get('phone')->setRequired(false);
            } elseif ($action == 'change-password') {
                $inputFilter->get('password-old')->setRequired(true);
                $inputFilter->get('username')->setRequired(false);
                $inputFilter->get('email')->setRequired(false);
                $inputFilter->get('phone')->setRequired(false);
            }
            
                        
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }
}