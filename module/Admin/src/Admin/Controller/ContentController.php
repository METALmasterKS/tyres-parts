<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Zend\Authentication\AuthenticationService;

class ContentController extends AbstractActionController {

    public function indexAction() {
        $view = new ViewModel();

        $sectionId = $this->params()->fromRoute('id', 0);
        
        $sectionTable = $this->getServiceLocator()->get('ContentModelSectionTable');
        $sections = $sectionTable->getSections(['parentId' => $sectionId, 'order' => 'sort asc']);

        $view->setVariables(array(
            'sectionId' => $sectionId,
            'sections' => $sections,
        ));
        
        return $view;
    }
    
    public function addSectionAction() {
        $sectionId = $this->params()->fromRoute('id', 0);
        $sectionTable = $this->getServiceLocator()->get('ContentModelSectionTable');

        $form = new \Content\Form\Section();
        
        if ($this->params()->fromPost('addSection') != null) {
            $section = new \Content\Model\Section($this->getServiceLocator());
            $form->setInputFilter($section->getInputFilter());
            $form->setData($this->getRequest()->getPost());
            
            if ($form->isValid()){
                $section->exchangeArray(array_merge(
                    $form->getData(),
                    ['parentId' => $sectionId]
                ));
                $sectionTable->saveSection($section);
                
                $this->FlashMessenger()->addSuccessMessage('Раздел добавлен.');
                
                $referer = $this->referer();
                if ($referer->getRouteName() != null) 
                    return $this->redirect()->toRoute($referer->getRouteName(), $referer->getParams());
                else
                    return $this->redirect()->toRoute('admin/default', array('controller' => 'content', 'action' => 'index'));
            }
        }
        
        return new ViewModel(array(
            'form' => $form,
            )
        );
    }
    
    public function editSectionAction() {
        $sectionTable = $this->getServiceLocator()->get('ContentModelSectionTable');

        $form = new \Content\Form\Section();
        
        $sectionId = $this->params()->fromRoute('id', '');
        $section = $sectionTable->getSection($sectionId);
        $form->setData($section->getArrayCopy());
        
        if ($this->params()->fromPost('saveSection') != null) {
            $form->setInputFilter($section->getInputFilter('edit'));
            $post = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                ['id' => $sectionId, 'parentId' => $section->parentId]   
            );
            $form->setData($post);
            
            if ($form->isValid()) {
                $section->exchangeArray($form->getData());
                
                $sectionTable->saveSection($section);
                
                $this->FlashMessenger()->addSuccessMessage('Раздел сохранен.');
                
                
                return $this->redirect()->toRoute('admin/default', array('controller' => 'content', 'action' => 'index', 'id' => $section->parentId));
            }
        }
        
        return new ViewModel(array(
            'form' => $form,
            )
        );
    }
    
    public function removeSectionAction(){
        if (!$this->getRequest()->isXmlHttpRequest()) 
            return array();
        
        $brandId = $this->getRequest()->getPost('brandid');
        
        $brandsTable = $this->getServiceLocator()->get('TyresModelBrandTable');
        $brandsTable->deleteBrand($brandId);
        
        $jsonModel = new \Zend\View\Model\JsonModel();
        $jsonModel->setVariables(array(
            'success' => true,
        ));
        return $jsonModel;
    }
    
    public function textsAction() {
        $view = new ViewModel();

        $sectionId = $this->params()->fromRoute('id', 0);
        
        $sectionTable = $this->getServiceLocator()->get('ContentModelSectionTable');
        $section = $sectionTable->getSection($sectionId);
        
        $textTable = $this->getServiceLocator()->get('ContentModelTextTable');
        $texts = $textTable->getTexts(['sectionId'=>$sectionId, 'order'=>'sort asc']);
        
        foreach ($texts as $text) {
            $section->addText($text);
        }
        

        $view->setVariables(array(
            'sectionId' => $sectionId,
            'section' => $section,
        ));
        
        return $view;
        
        return new ViewModel(array(
            
        ));
    }
    
    public function addTextAction() {
        $sectionId = $this->params()->fromRoute('id', 0);
        $textTable = $this->getServiceLocator()->get('ContentModelTextTable');

        $form = new \Content\Form\Text();
        
        if ($this->params()->fromPost('addText') != null) {
            $text = new \Content\Model\Text($this->getServiceLocator());
            $form->setInputFilter($text->getInputFilter());
            $form->setData($this->getRequest()->getPost());
            
            if ($form->isValid()){
                $text->exchangeArray(array_merge(
                    $form->getData(),
                    ['sectionId' => $sectionId]
                ));
                $textTable->saveText($text);
                
                $this->FlashMessenger()->addSuccessMessage('Текст добавлен.');
                
               return $this->redirect()->toRoute('admin/default', array('controller' => 'content', 'action' => 'texts', 'id' => $sectionId));
            }
        }
        
        return new ViewModel(array(
            'form' => $form,
            )
        );
    }
    
    public function editTextAction() {
        $textTable = $this->getServiceLocator()->get('ContentModelTextTable');

        $form = new \Content\Form\Text();
        
        $textId = $this->params()->fromRoute('id', '');
        $text = $textTable->getText($textId);
        $form->setData($text->getArrayCopy());
        
        if ($this->params()->fromPost('saveText') != null) {
            $form->setInputFilter($text->getInputFilter('edit'));
            $post = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                ['id' => $textId, 'sectionId' => $text->sectionId]   
            );
            $form->setData($post);
            
            if ($form->isValid()) {
                $text->exchangeArray($form->getData());
                
                $textTable->saveText($text);
                
                $this->FlashMessenger()->addSuccessMessage('Статья сохранена.');
                
                
                return $this->redirect()->toRoute('admin/default', array('controller' => 'content', 'action' => 'texts', 'id' => $text->sectionId));
            }
        }
        
        return new ViewModel(array(
            'form' => $form,
            )
        );
    }
    
    public function savesortAction() {
        if (!$this->getRequest()->isXmlHttpRequest()) 
            return array();
        
        $sort = $this->getRequest()->getPost('sort');
        if (!is_array($sort))
            return new JsonModel(['success' => false]);
            
        $parentGroupId = $this->getRequest()->getPost('parentId', 0);
        
        if ($this->params()->fromRoute('sub') == 'sections')
            $tableService = 'ContentModelSectionTable';
        elseif ($this->params()->fromRoute('sub') == 'texts')
            $tableService = 'ContentModelTextTable';
        
        $table = $this->getServiceLocator()->get($tableService);
        $table->saveSorting($parentGroupId, $sort);
        
        $jsonModel = new JsonModel();
        $jsonModel->setVariables(array(
            'success' => true,
        ));
        return $jsonModel;
    }
    
    public function browseFilesAction() {
        $view = new ViewModel();
        $view->setTerminal(true);
        return $view;
    }

}
