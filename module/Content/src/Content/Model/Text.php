<?php

namespace Content\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Text implements InputFilterAwareInterface
{
    public $id;
    public $sectionId;
    public $name;
    public $alias;
    public $text;
    public $image;


    private $serviceLocator; 
    
    protected $inputFilter;  

    public function __construct($serviceLocator = null)
    {
        $this->serviceLocator = $serviceLocator; 
    }
    
    public function exchangeArray($data)
    {
        $this->id       = isset($data['id'])        ? (int)     $data['id'] : null;
        $this->sectionId  = isset($data['sectionId'])   ? (int)     $data['sectionId'] : '';
        $this->name    = isset($data['name'])     ? (string)     $data['name'] : '';
        $this->alias   = isset($data['alias'])    ? (string)     $data['alias'] : '';
        $this->text = isset($data['text'])  ? (string)  $data['text'] : '';
        $this->image = isset($data['image'])  ? (string)  $data['image'] : '';
    }
    
    public function getArrayCopy() {
        return get_object_vars($this);
    }
    
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter($action = 'add')
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array('name' => 'id', 'required' => false, 'filters' => array( array('name' => 'Int'), )));
            $inputFilter->add(array('name' => 'sectionId', 'required' => false, 'filters' => array( array('name' => 'Int'), )));
            $inputFilter->add(array('name' => 'name', 'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 255,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array('name' => 'alias', 'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'StringToLower'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 255,
                        ),
                    ),
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/[a-z0-9_-]+/iu',
                        ),
                    ),
                ),
            ));
            
            

            if (isset($this->serviceLocator)) {
                $inputFilter->get('name')->getValidatorChain()->addByName('Db\NoRecordExists', array(
                    'table'   => 'content_texts',
                    'field'   => 'alias',
                    'exclude' => $this->id != null ? array('field' => 'id', 'value' => $this->id) : null,
                    'adapter' => $this->serviceLocator->get('Zend\Db\Adapter\Adapter')
                ));
            }
            
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
