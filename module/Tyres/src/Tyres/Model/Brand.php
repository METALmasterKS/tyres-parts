<?php

namespace Tyres\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Brand implements InputFilterAwareInterface
{

    public $id;
    public $name;
    public $aliases;
    
    private $tyresCount;
    private $models;

    private $serviceLocator; //serviceManager
    
    protected $inputFilter;  

    public function __construct($serviceLocator = null)
    {
        $this->serviceLocator = $serviceLocator; 
    }
    
    public function exchangeArray($data)
    {
        $this->id    = (isset($data['id']))    ? (int) $data['id'] : null;
        $this->name  = (isset($data['name']))  ? $data['name'] : null;
        $this->aliases  = (isset($data['aliases']))  ? $data['aliases'] : null;
        $this->tyresCount  = (isset($data['tyresCount']))  ? $data['tyresCount'] : null;
    }
    
    public function getArrayCopy() {
        return get_object_vars($this);
    }
    
    public function getTyresCount(){
        return $this->tyresCount;
    }
    
    public function setModels($models) {
        $this->models = $models;
    }
    
    public function getModels() {
        return $this->models;
    }
    
    public function addModel(\Tyres\Model\Model $model){
        $this->models[] = $model;
        return $this;
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
                            'max' => 128,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array('name' => 'aliases', 'required' => false,
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

            if (isset($this->serviceLocator)) {
                $inputFilter->get('name')->getValidatorChain()->addByName('Db\NoRecordExists', array(
                    'table'   => 'tyres_brands',
                    'field'   => 'name',
                    'exclude' => $this->id != null ? array('field' => 'id', 'value' => $this->id) : null,
                    'adapter' => $this->serviceLocator->get('Zend\Db\Adapter\Adapter')
                ));
            }
            
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }


}