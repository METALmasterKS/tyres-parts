<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Cart\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class IndexController extends AbstractActionController
{

    //@TODO наткнулся на проблему которая заключается в разрыве связи между корзиной и шинами при импорте каталога, нужно разбираться до конца с импортом.
    
    
    public function indexAction()
    {
        $cart = $this->getServiceLocator()->get('Cart');
        if (count($cart->getItems('tyres'))) {
            //загрузка цен шин из корзины
            $tyresPrices = $this->serviceLocator->get('TyresModelPriceTable')->getVariants(['ids' => $cart->getObjectIds('tyres')]);
            
        }
        
        $view = new ViewModel();
        $view->setVariables(array( 'cart' => $cart, ));
        
        if ($this->getRequest()->isXmlHttpRequest()) {
            $view->setTerminal(true)
               ->setTemplate('cart/index/widget');

            $htmlOutput = $this->getServiceLocator()
                     ->get('viewrenderer')
                     ->render($view);
            
            $jsonModel = new JsonModel();
            $jsonModel->setVariables(array(
                'success' => true,
                'cartTotalSumm' => $cart->getTotalSumm(),
                'cartTotalCount' => $cart->getTotalCount(),
                'html' => $htmlOutput,
            ));
            return $jsonModel;
        } else {
            return $view;
        }
    }

    public function addtocartAction()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            return;
        }
        
        $count = $this->getRequest()->getPost('count');
        $objectId = $this->getRequest()->getPost('objectId');
        $type = $this->getRequest()->getPost('type');
        
        $cart = $this->getServiceLocator()->get('Cart');
        
        if ($type == 'tyres') {
            $tyreTable = $this->getServiceLocator()->get('TyresModelPriceTable');
            $tyrePrice = $tyreTable->getPrice($objectId);
            $addItem = $cart->createItem('tyres', $tyrePrice->id, $count);
            $cart->addItem($addItem);
        }
        
        // getTotalSumm 
        $tyrePrices = $this->serviceLocator->get('TyresModelPriceTable')->getPrices( ['ids' => $cart->getObjectIds('tyres')] );
        foreach ($tyrePrices as $key => $price) {
            $cart->addTyre($price);
        }
        
        $jsonModel = new JsonModel();
        $jsonModel->setVariables(array(
            'success' => true,
            'cartTotalSumm' => $cart->getTotalSumm(),
            'cartTotalCount' => $cart->getTotalCount(),
            //'cartItemCount' => $item->count,
        ));

        return $jsonModel;
    }
    
    public function updateitemcountAction()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            return;
        }
        
        $count = $this->getRequest()->getPost('count');
        $variantId = $this->getRequest()->getPost('variantId');
        $cartItemId = $this->getRequest()->getPost('cartItemId');
        
        $cart = $this->getServiceLocator()->get('Cart');
        if ($cart instanceof \Cart\Model\Cart) {
            foreach ($cart->getItems() as $item) {
                if ($item->variant_id == $variantId) {
                    $item->count = $count;
                    $cartItemTable = $this->getServiceLocator()->get('CartModelItemTable');
                    $cartItemTable->saveItem($item);
                    break;
                }
            }
        } elseif ($cart instanceof \Cart\Model\CartSession) {
            foreach ($cart->getItems() as $item) {
                if ($item->variant_id == $variantId) {
                    $item->count = $count;
                    break;
                }
            }
        }
        
        // getTotalSumm 
        $variants = $this->serviceLocator->get('CatalogModelVariantTable')->getVariants(null, ['ids' => $cart->getVariantIds()]);
        foreach ($variants as $key => $variant) {
            $cart->addVariant($variant);
        }
        
        $jsonModel = new JsonModel();
        $jsonModel->setVariables(array(
            'success' => true,
            'cartTotalSumm' => $cart->getTotalSumm(),
            'cartTotalCount' => $cart->getTotalCount(),
        ));

        return $jsonModel;
    }
    
    public function removefromcartAction()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) 
            return array();
        
        $variantId = $this->getRequest()->getPost('variantId');
        
        $cart = $this->getServiceLocator()->get('Cart');
        $cart->deleteItem($variantId);
        
        $jsonModel = new JsonModel();
        $jsonModel->setVariables(array(
            'success' => true,
        ));

        return $jsonModel;
    }


}
