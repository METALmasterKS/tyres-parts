<?php

namespace Content\Navigation;


class Content
{
    private $serviceLocator;

    private $navigation;
    private $cache;
    private $sectionTable;
    private $textTable;


    public function __construct($serviceLocator) {
        $this->serviceLocator = $serviceLocator;
        $this->navigation = $serviceLocator->get('navigation');
        $this->cache = $serviceLocator->get('fileCache');
        $this->sectionTable = $serviceLocator->get('ContentModelSectionTable');
        $this->textTable = $serviceLocator->get('ContentModelTextTable');
        
        $this->loadContentNavigation();
        
        return $this;
    }
    
    private function loadContentNavigation(){
        
        $sections = $this->sectionTable->getSections(['parentId' => \Content\Model\Section::SECTION_ROOT, 'order' => 'sort asc']);
        $home = $this->navigation->findOneBy('id', 'home');
        
        //$cache->removeItem('ContentNavigatonPages'); //раскоментить для постоянного обновления
        if ($this->cache->hasItem('ContentNavigatonPages')) {
            $pages = $this->cache->getItem('ContentNavigatonPages');
        } else {
            $pages = $this->pagesRecursive( $sections );
            $this->cache->setItem('ContentNavigatonPages', $pages);
        }
        foreach ($pages as $page)
            $home->addPage($page);
        
        $texts = $this->textTable->getTexts(['order'=>'sort asc']);
        foreach ($texts as $text){
            $sectionPage = $this->navigation->findOneBy('id', 'home-content-section-'.$text->sectionId);
            if ($sectionPage instanceof \Zend\Navigation\Page\Mvc)
                $sectionPage->addPage(new \Zend\Navigation\Page\Mvc($this->pageTextConfig($text)));
        }
        
    }
    
    private function pagesRecursive($sections) {
        $pages = array();
        foreach($sections as $section) {
            $pages[$section->id] = new \Zend\Navigation\Page\Mvc($this->pageConfig($section));
            $sections = $this->sectionTable->getSections(['parentId' => $section->id, 'order' => 'sort asc']);
            if ($sections->count())
                $pages[$section->id]->setPages( $this->pagesRecursive($sections) );
        }
        return $pages;
    }
    
    private function pageConfig($section){
        return array(
            'label' => $section->name,
            'id' => 'home-content-section-'.$section->id,
            'route' => 'home/content/section',
            'params' => [
                'alias' => $section->alias,
            ],
            'sectionId' => $section->id,
            'parentId' => $section->parentId,
        );
    }
    
    private function pageTextConfig($text){
        return array(
            'label' => $text->name,
            'id' => 'home-content-text-'.$text->id,
            'route' => 'home/content/page',
            'params' => [
                'alias' => $text->alias,
            ],
            'textId' => $text->id,
            'sectionId' => $text->sectionId,
            'visible' => false,
        );
    }


}
