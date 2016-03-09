<?php

namespace Tyres\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Model implements InputFilterAwareInterface
{

    public $id;
    public $name;
    public $aliases;
    public $brandId;
    public $season;
    
    private $brand;
    private $tyres;
    
    private $serviceLocator; 
    
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
        $this->brandId  = (isset($data['brandId']))  ? $data['brandId'] : null;
        $this->season  = (isset($data['season']))  ? $data['season'] : null;
    }
    
    public function getArrayCopy() {
        return get_object_vars($this);
    }
    
    public function setBrand(\Tyres\Model\Brand $brand) {
        $this->brand = $brand;
    }
    
    public function getBrand() {
        return $this->brand;
    }
    
    public function setTyres($tyres) {
        $this->tyres = $tyres;
    }
    
    public function getTyres() {
        return $this->tyres ?: array();
    }
    
    public function addTyre(\Tyres\Model\Tyre $tyre){
        $this->tyres[] = $tyre;
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