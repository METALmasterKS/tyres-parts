<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Tyres\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BrandsController extends AbstractActionController {

    public function indexAction() {
        $view = new ViewModel();
        
        $brandTable = $this->getServiceLocator()->get('TyresModelBrandTable');
        $brands = $brandTable->getBrands(['tyresCountLoad' => true, 'order' => 'name', 'tyresCountGreaterThan' => 0, ]);
        
        $alphabetBrands = array();
        $trash = array();
        foreach ($brands as $brand) {
            $A = mb_strtoupper(mb_substr($brand->name, 0, 1));
            if (preg_match("/^[А-ЯA-Z]|\d$/iu", $A)){
                if (preg_match("/\d/u", $A)) 
                    $A = '0-9';
                $alphabetBrands[$A][] = $brand;
            } else {
                $trash[] = $brand;
            }
        }
        $alphabetBrands['Остальные'] = $trash;
        
        $view->setVariables(array(
            //'brands' => $brands,
            'alphabetBrands' => $alphabetBrands,
        ));
        
        return $view;
    }
    
    public function brandAction() {
        $view = new ViewModel();
        
        $brandName = $this->params('name');
        $brandTable = $this->getServiceLocator()->get('TyresModelBrandTable');
        $brands = $brandTable->getBrands(['name' => $brandName]);
        $brand = $brands->current();
        
        $this->NavigationTree($brand);
        
        $modelTable = $this->getServiceLocator()->get('TyresModelModelTable');
        $tyreModels = $modelTable->getModels(['brandId' => $brand->id]);
        
        $view->setVariables(array(
                'brand' => $brand,
                'tyreModels' => $tyreModels,
                'lastId' => (int) $this->params()->fromRoute('lastId'),
                'isXmlHttpRequest' => $this->getRequest()->isXmlHttpRequest(),
            ));
        
        if ($this->getRequest()->isXmlHttpRequest())
            $view->setTerminal(true);
        
        return $view;
    }
    
}
