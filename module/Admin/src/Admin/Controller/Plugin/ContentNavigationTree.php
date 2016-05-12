<?php

namespace Admin\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class ContentNavigationTree extends AbstractPlugin
{
    private $section;
    private $text;
    
    private $navigation;


    public function __invoke($section = null, $text = null) {
        $this->navigation = $this->getController()->getServiceLocator()->get('navigation');
        $this->cache = $this->getController()->getServiceLocator()->get('fileCache');
        $this->sectionTable = $this->getController()->getServiceLocator()->get('ContentModelSectionTable');
        
        $this->section = $section;
        $this->text = $text;

        $this->loadSectionNavigation();
        
        return $this;
    }
    
    private function loadSectionNavigation() {
        $sections = $this->sectionTable->getSections(['parentId' => \Content\Model\Section::SECTION_ROOT, 'order' => 'sort asc']);
        $contentRoot = $this->navigation->findOneBy('id', 'admin-content');
        
        //$cache->removeItem('AdminContentNavigatonPages'); //раскоментить для постоянного обновления
        if ($this->cache->hasItem('AdminContentNavigatonPages')) {
            $pages = $this->cache->getItem('AdminContentNavigatonPages');
        } else {
            $pages = $this->pagesRecursive( $sections );
            $this->cache->setItem('AdminContentNavigatonPages', $pages);
        }
        foreach ($pages as $page)
            $contentRoot->addPage($page);
        
        if ($this->section instanceof \Content\Model\Section) {
            $page = $this->navigation->findOneBy('id', 'admin-content-section-'.$this->section->id);
            $page->setActive(true);
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
            'id' => 'admin-content-section-'.$section->id,
            'route' => 'admin/default',
            'params' => [
                'controller' => 'content',
                'action' => 'index',
                'id' => $section->id,
            ],
            'sectionId' => $section->id,
            'parentId' => $section->parentId,
        );
    }
    
    public function addSection() {
        if ($this->section instanceof \Content\Model\Section) {
            $page = $this->navigation->findOneBy('id', 'admin-content-section-' . $this->section->id);
            if ($page instanceof \Zend\Navigation\Page\Mvc) {
                $page->addPage([
                    'label' => 'Добавление раздела в раздел "' . $this->section->name . '"',
                    'id' => 'admin-content-section-add-' . $this->section->id,
                    'route' => 'admin/default',
                    'params' => [
                        'controller' => 'content',
                        'action' => 'addsection',
                        'id' => $this->section->id,
                    ],
                ]);
                $page = $this->navigation->findOneBy('id', 'admin-content-section-add-' . $this->section->id);
                $page->setActive(true);
            }
        } else {
            $page = $this->navigation->findOneBy('id', 'admin-content');
            if ($page instanceof \Zend\Navigation\Page\Mvc) {
                $page->addPage([
                    'label' => 'Добавление раздела',
                    'id' => 'admin-content-section-add',
                    'route' => 'admin/default',
                    'params' => [
                        'controller' => 'content',
                        'action' => 'addsection',
                    ],
                ]);
                $page = $this->navigation->findOneBy('id', 'admin-content-section-add');
                $page->setActive(true);
            }
        }
        return $this;
    }
    
    public function editSection() {
        if ($this->section instanceof \Content\Model\Section) {
            $page = $this->navigation->findOneBy('id', 'admin-content-section-' . $this->section->id);
            if ($page instanceof \Zend\Navigation\Page\Mvc) {
                $page->addPage([
                    'label' => 'Редактирование раздела "' . $this->section->name . '"',
                    'id' => 'admin-content-section-edit-' . $this->section->id,
                    'route' => 'admin/default',
                    'params' => [
                        'controller' => 'content',
                        'action' => 'editsection',
                        'id' => $this->section->id,
                    ],
                ]);
                $page = $this->navigation->findOneBy('id', 'admin-content-section-edit-' . $this->section->id);
                $page->setActive(true);
            }
        }
        return $this;
    }
    
    public function texts()
    {
        $page = $this->navigation->findOneBy('id', 'admin-content-section-' . $this->section->id);
        if ($page instanceof \Zend\Navigation\Page\Mvc) {
            $page->addPage([
                'label' => 'Статьи раздела "' . $this->section->name . '"',
                'id' => 'admin-content-section-texts-' . $this->section->id,
                'route' => 'admin/default',
                'params' => [
                    'controller' => 'content',
                    'action' => 'texts',
                    'id' => $this->section->id,
                ],
            ]);
            $page = $this->navigation->findOneBy('id', 'admin-content-section-texts-' . $this->section->id);
            $page->setActive(true);
        }
        return $this;
    }
    
    public function addText() {
        if ($this->section instanceof \Content\Model\Section) {
            $page = $this->navigation->findOneBy('id', 'admin-content-section-texts-'.$this->section->id);
            if ($page instanceof \Zend\Navigation\Page\Mvc) {
                $page->addPage([
                    'label' => 'Добавление статьи в раздел "' . $this->section->name . '"',
                    'id' => 'admin-content-section-'.$this->section->id.'-add-text',
                    'route' => 'admin/default',
                    'params' => [
                        'controller' => 'content',
                        'action' => 'addtext',
                        'id' => $this->section->id,
                    ],
                ]);
                $page = $this->navigation->findOneBy('id', 'admin-content-section-'.$this->section->id.'-add-text');
                $page->setActive(true);
            }
        } 
        return $this;
    }
    
    public function editText() {
        if ($this->text instanceof \Content\Model\Text) {
            $page = $this->navigation->findOneBy('id', 'admin-content-section-texts-' . $this->section->id);
            if ($page instanceof \Zend\Navigation\Page\Mvc) {
                $page->addPage([
                    'label' => 'Редактирование статьи "' . $this->text->name . '"',
                    'id' => 'admin-content-section-' . $this->section->id.'-edit-text',
                    'route' => 'admin/default',
                    'params' => [
                        'controller' => 'content',
                        'action' => 'editsection',
                        'id' => $this->text->id,
                    ],
                ]);
                $page = $this->navigation->findOneBy('id', 'admin-content-section-' . $this->section->id.'-edit-text');
                $page->setActive(true);
            }
        }
        return $this;
    }


}
