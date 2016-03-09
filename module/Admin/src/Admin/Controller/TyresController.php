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

class TyresController extends AbstractActionController {

    public function indexAction() {
        $view = new ViewModel();

        return $view;
    }
    
    public function newvariantsAction(){ return; // нераспаршенные шины
        $data = ['pagination' => true];
        $form = new \Admin\Form\SearchNewVariantsForm();
        
        $deletedStatus = $this->getServiceLocator()->get('CatalogModelStatusTable')->getDeletedStatus();
        $keyVal = [$deletedStatus::DELETED_STATUS => $deletedStatus::DELETED_STATUS_NAME];
        $statuses = array($deletedStatus::DELETED_STATUS => $deletedStatus);
        foreach ($this->getServiceLocator()->get('CatalogModelStatusTable')->fetchAll() as $status) {
            $keyVal[$status->number] = $status->name;
            $statuses[$status->number] = $status;
        }        
        $form->get('status')->setValueOptions($keyVal);
            
        if ($this->params()->fromQuery('search') != null) {
            $form->setData($this->params()->fromQuery());
            //$form->getInputFilter()->remove('status');
            if ($form->isValid()){
                foreach ($form->getData() as $key => $value) {//только те параметры которые заполнены
                    if ($value != null)
                        $data[$key] = $value;
                }
            }
        }
        
        $newVariants = $this->getServiceLocator()->get('CatalogModelNewVariantTable')->getNewVariants($data);
        if ($newVariants instanceof \Zend\Paginator\Paginator)
            $newVariants->setCurrentPageNumber((int) $this->params()->fromRoute('page'));
        
        
        return new ViewModel(array(
            'newVariants' => $newVariants,
            'statuses' => $statuses,
            'form' => $form,
            'query' => $this->params()->fromQuery(),
            )
        );
    }

    public function providersAction() {
        $view = new ViewModel();
        
        $providerTable = $this->getServiceLocator()->get('TyresModelProviderTable');
        $providers = $providerTable->getProviders();
        $view->setVariable('providers', $providers);
        return $view;
    }
    
    public function importAction() {
        $files = $this->GetImportFiles('tyres');
        
        if ($this->getRequest()->isPost()) {
            $this->CliTaskManager()->saveTaskStatus('Import/Tyres', \Cli\Controller\Plugin\CliTaskManager::START);
            $this->FlashMessenger()->addSuccessMessage('Задание на импорт поставлено, обновление займет некоторое время.');
            $this->redirect()->toRoute('admin/default', [ 'controller' => 'Tyres', 'action' => 'Import', ]);
        }
        
        return new ViewModel(array(
            'files' => $files,
        ));
    }
    

}
