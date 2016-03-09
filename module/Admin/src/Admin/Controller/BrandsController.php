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

class BrandsController extends AbstractActionController {

    public function indexAction() {
        $view = new ViewModel();
        
        $brandsTable = $this->getServiceLocator()->get('TyresModelBrandTable');
        
        $data = ['pagination' => true, 'tyresCountLoad' => true, 'order' => 'name'];
        $form = new \Admin\Form\SearchBrandsForm();
        $form->setAttribute('action', $this->url()->fromRoute('admin/default', ['controller' => 'brands', 'action' => 'index', 'sub' => 'search']));
        if ($this->params()->fromQuery('search') != null) {
            $form->setData($this->params()->fromQuery());
            if ($form->isValid()) {
                foreach ($form->getData() as $key => $value) {//только те параметры которые заполнены
                    if ($value != null)
                        $data[$key] = is_integer(strpos($key,'date_')) ? strtotime($value) : $value;
                }
            }
        }
        
        $brands = $brandsTable->getBrands($data);
        if ($brands instanceof \Zend\Paginator\Paginator)
            $brands->setCurrentPageNumber((int) $this->params()->fromRoute('page'));
        
        $view->setVariables(array(
                'brands' => $brands,
                'form' => $form,
                'sub' => $this->params()->fromRoute('sub'),
                'query' => $this->params()->fromQuery(),
            ));

        return $view;
    }
    
    public function addAction() {
        $brandsTable = $this->getServiceLocator()->get('TyresModelBrandTable');

        $form = new \Tyres\Form\Brand();
        
        if ($this->params()->fromPost('addBrand') != null) {
            $brand = new \Tyres\Model\Brand($this->getServiceLocator());
            $form->setInputFilter($brand->getInputFilter());
            $form->setData($this->getRequest()->getPost());
            
            if ($form->isValid()){
                $brand->exchangeArray($form->getData());
                $brandsTable->saveBrand($brand);
                
                $this->FlashMessenger()->addSuccessMessage('Бренд добавлен.');
                
                $referer = $this->referer();
                if ($referer->getRouteName() != null) 
                    return $this->redirect()->toRoute($referer->getRouteName(), $referer->getParams());
                else
                    return $this->redirect()->toRoute('admin/default', array('controller' => 'brands', 'action' => 'index'));
            }
        }
        
        return new ViewModel(array(
            'form' => $form,
            )
        );
    }
    
    public function editAction() {
        $brandsTable = $this->getServiceLocator()->get('TyresModelBrandTable');

        $form = new \Tyres\Form\Brand();
        
        $brandId = $this->params()->fromRoute('id');
        $brand = $brandsTable->getBrand($brandId);
        $form->setData($brand->getArrayCopy());
        
        if ($this->params()->fromPost('saveBrand') != null) {
            $form->setInputFilter($brand->getInputFilter('edit'));
            $post = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                ['id' => $brandId]    
            );
            $form->setData($post);
            
            if ($form->isValid()) {
                $brand->exchangeArray($form->getData());
                
                $brandsTable->saveBrand($brand);
                
                $this->FlashMessenger()->addSuccessMessage('Бренд сохранен.');
                
                $referer = $this->referer();
                if ($referer->getRouteName() != null) 
                    return $this->redirect()->toRoute($referer->getRouteName(), $referer->getParams());
                else
                    return $this->redirect()->toRoute('admin/default', array('controller' => 'brands', 'action' => 'index'));
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
        
        $brandId = $this->getRequest()->getPost('brandid');
        
        $brandsTable = $this->getServiceLocator()->get('TyresModelBrandTable');
        $brandsTable->deleteBrand($brandId);
        
        $jsonModel = new \Zend\View\Model\JsonModel();
        $jsonModel->setVariables(array(
            'success' => true,
        ));
        return $jsonModel;
    }
    

}
