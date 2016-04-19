<?php

namespace Content\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class ContentNavigationTree extends AbstractPlugin
{
    private $section;
    private $text;
    
    private $navigation;
    private $sectionTable;
    private $textTable;


    public function __invoke($section = null, $text = null) {
        $this->navigation = $serviceLocator->get('navigation');
        
        $this->section = $section;
        $this->text = $text;

        if ($this->section instanceof \Content\Model\Section)
            $this->loadSectionNavigation();
        
        if ($this->text instanceof \Content\Model\Text)
            $this->loadTextNavigation(); 
        
        return $this;
    }
    
    private function loadSectionNavigation() {
        $page = $this->navigation->findOneBy('id', 'home-tyres');
        if ($page instanceof \Zend\Navigation\Page\Mvc){
            $page->addPage([
                'label' => $this->section->name,
                'id' => 'tyre-brand-'.$this->section->id,
                'route' => 'home/tyres/brands/brand',
                'params' => [
                    'name' => $this->section->name,
                ]]);
            $page = $this->navigation->findOneBy('id', 'tyre-brand-'.$this->section->id);
            $page->setActive(true);
        }
    }
    
    private function loadModelNavigation() {
        $page = $this->navigation->findOneBy('id', 'tyre-brand-'.$this->section->id);
        if ($page instanceof \Zend\Navigation\Page\Mvc){
            $page->addPage([
                'label' => $this->text->name,
                'id' => 'tyre-model-'.$this->text->id,
                'route' => 'home/tyres/tyre',
                'params' => [
                    'brandName' => $this->section->name,
                    'modelName' => $this->text->name,
                ]]);
            $page = $this->navigation->findOneBy('id', 'tyre-model-'.$this->text->id);
            $page->setActive(true);
        }
    }


}
