<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Content\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController {

    public function indexAction() { //section
        $view = new ViewModel();
        
        $sectionAlias = $this->params()->fromRoute('alias', '');
        
        $sectionTable = $this->getServiceLocator()->get('ContentModelSectionTable');
        $section = $sectionTable->getSectionByAlias($sectionAlias);
        
        $navigation = $this->getServiceLocator()->get('navigation');
        $page = $navigation->findOneBy('id', 'home-content-section-'.$section->id);
        $page->setActive(true);
        
        $sections = $sectionTable->getSections(['parentId' => $section->id, 'order' => 'sort asc']);
        
        $textTable = $this->getServiceLocator()->get('ContentModelTextTable');
        $texts = $textTable->getTexts(['sectionId'=>$section->id, 'order' => 'sort asc']);
        
        if ($sections->count() == 0 && $texts->count() == 1){
            foreach ($texts as $text)
                return $this->forward()->dispatch('Content\Controller\Index', array('action' => 'text', 'alias' => $text->alias));
        }
        
        if ($sections->count() == 0) {
            $neighbors = $sectionTable->getSections(['parentId' => $section->parentId, 'order' => 'sort asc']);
            $view->setVariable('neighbors', $neighbors);
        }

        foreach ($sections as $sect) {
            $sect->setParentSection($section);
            $section->addSection($sect);
        }
        foreach ($texts as $text) {
            $section->addText($text);
        }
        
        $view->setVariables(array(
            'sectionId' => $section->id,
            'section' => $section,
        ));
        
        return $view;
    }
    
    public function textAction(){
        $view = new ViewModel();
        
        $textAlias = $this->params()->fromRoute('alias', '');
        
        $textTable = $this->getServiceLocator()->get('ContentModelTextTable');
        $text = $textTable->getTextByAlias($textAlias);
        
        $navigation = $this->getServiceLocator()->get('navigation');
        $page = $navigation->findOneBy('id', 'home-content-text-'.$text->id);
        $page->setActive(true);

        $view->setVariables(array(
            'sectionId' => $text->sectionId,
            'text' => $text,
        ));

        
        return $view;
    }
    

}
