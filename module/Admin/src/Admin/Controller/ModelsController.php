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

class ModelsController extends AbstractActionController {

    public function indexAction() {
        $view = new ViewModel();
        
        $brandsTable = $this->getServiceLocator()->get('TyresModelBrandTable');
        $brandId = $this->params()->fromRoute('id');
        $brand = $brandsTable->getBrand($brandId);
        
        $navigationTree = $this->NavigationTree($brand, null, null, true);
        $navigationTree->adminModels();
        
        $data = ['brandId' => $brand->id, 'pagination' => true, 'tyresCountLoad' => true, 'order' => 'name'];
        $form = new \Admin\Form\SearchModels();
        $form->setAttribute('action', $this->url()->fromRoute('admin/default', ['controller' => 'models', 'action' => 'index', 'id' => $brand->id, 'sub' => 'search']));
        if ($this->params()->fromQuery('search') != null) {
            $form->setData($this->params()->fromQuery());
            if ($form->isValid()) {
                foreach ($form->getData() as $key => $value) {//только те параметры которые заполнены
                    if ($value != null)
                        $data[$key] = is_integer(strpos($key,'date_')) ? strtotime($value) : $value;
                }
            }
        }
        
        $modelTable = $this->getServiceLocator()->get('TyresModelModelTable');
        
        $models = $modelTable->getModels($data);
        if ($models instanceof \Zend\Paginator\Paginator)
            $models->setCurrentPageNumber((int) $this->params()->fromRoute('page'));
        
        $view->setVariables(array(
                'brand' => $brand,
                'models' => $models,
                'form' => $form,
                'sub' => $this->params()->fromRoute('sub'),
                'query' => $this->params()->fromQuery(),
            ));

        return $view;
    }
    
    public function addAction() {
        $form = new \Tyres\Form\Model();
        
        if ($this->params()->fromPost('addModel') != null) {
            
            $brandsTable = $this->getServiceLocator()->get('TyresModelBrandTable');
            $brandId = $this->params()->fromRoute('id');
            $brand = $brandsTable->getBrand($brandId);
            
            $model = new \Tyres\Model\Model($this->getServiceLocator());
            $form->setInputFilter($model->getInputFilter());
            $form->setData($this->getRequest()->getPost());
            
            if ($form->isValid()){
                $model->exchangeArray($form->getData());
                $model->brandId = $brand->id;
                $modelTable = $this->getServiceLocator()->get('TyresModelModelTable');
                $modelTable->saveModel($model);
                
                $this->FlashMessenger()->addSuccessMessage('Модель добавлена.');
                
                $referer = $this->referer();
                if ($referer->getRouteName() != null) 
                    return $this->redirect()->toRoute($referer->getRouteName(), $referer->getParams());
                else
                    return $this->redirect()->toRoute('admin/default', array('controller' => 'models', 'action' => 'index'));
            }
        }
        
        return new ViewModel(array(
            'form' => $form,
            )
        );
    }
    
    public function editAction() {
        $modelTable = $this->getServiceLocator()->get('TyresModelModelTable');

        $form = new \Tyres\Form\Model();
        
        $modelId = $this->params()->fromRoute('id');
        $model = $modelTable->getModel($modelId);
        $form->setData($model->getArrayCopy());
        
        if ($this->params()->fromPost('saveModel') != null) {
            $form->setInputFilter($model->getInputFilter('edit'));
            $post = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                ['id' => $modelId]    
            );
            $form->setData($post);
            
            if ($form->isValid()) {
                $clonemodel = clone $model;
                $model->exchangeArray($form->getData());
                $model->brandId = $clonemodel->brandId;
                $modelTable->saveModel($model);
                
                $this->FlashMessenger()->addSuccessMessage('Модель сохранена.');
                
                $referer = $this->referer();
                if ($referer->getRouteName() != null) 
                    return $this->redirect()->toRoute($referer->getRouteName(), $referer->getParams());
                else
                    return $this->redirect()->toRoute('admin/default', array('controller' => 'models', 'action' => 'index'));
            }
        }
        
        return new ViewModel(array(
            'form' => $form,
            )
        );
    }
    
    public function removeAction(){
        if (!$this->getRequest()->isXmlHttpRequest()) 
            return array();
        
        $modelId = $this->getRequest()->getPost('brandid');
        
        $modelTable = $this->getServiceLocator()->get('TyresModelModelTable');
        $modelTable->deleteModel($modelId);
        
        $jsonModel = new \Zend\View\Model\JsonModel();
        $jsonModel->setVariables(array(
            'success' => true,
        ));
        return $jsonModel;
    }
    

}
