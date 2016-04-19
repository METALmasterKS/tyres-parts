<?php

namespace Content\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Section implements InputFilterAwareInterface
{
    const SECTION_ROOT = 0;
    
    public $id;
    public $parentId;
    public $name;
    public $alias;
    
    private $parentSection;
    private $texts;
    private $sections;
    
    private $serviceLocator; 
    
    protected $inputFilter;  

    public function __construct($serviceLocator = null)
    {
        $this->serviceLocator = $serviceLocator; 
    }
    
    public function exchangeArray($data)
    {
        $this->id    = (isset($data['id']))    ? (int) $data['id'] : '';
        $this->parentId  = (isset($data['parentId']))  ? $data['parentId'] : '';
        $this->name  = (isset($data['name']))  ? $data['name'] : '';
        $this->alias  = (isset($data['alias']))  ? $data['alias'] : '';
    }
    
    public function getArrayCopy() {
        return get_object_vars($this);
    }
    
    public function setParentSection(\Content\Model\Section $section) {
        $this->parentSection = $section;
    }
    
    public function getParentSection() {
        return $this->parentSection;
    }
    
    public function setTexts($texts) {
        $this->texts = $texts;
    }
    
    public function getTexts() {
        return $this->texts ?: array();
    }
    
    public function addText(\Content\Model\Text $text){
        $this->texts[] = $text;
        return $this;
    }
    
    public function setSections($sections) {
        $this->sections = $sections;
    }
    
    public function getSections() {
        return $this->sections ?: array();
    }
    
    public function addSection(\Content\Model\Section $section){
        $this->sections[] = $section;
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
            $inputFilter->add(array('name' => 'parentId', 'required' => false, 'filters' => array( array('name' => 'Int'), )));
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
                    'table'   => 'content_sections',
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