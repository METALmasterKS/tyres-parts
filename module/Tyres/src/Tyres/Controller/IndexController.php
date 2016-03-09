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

class IndexController extends AbstractActionController {

    public function indexAction() {
        $queryParamsNames = ['brandName', 'modelName', 'season', 'width', 'height', 'diameter' ,'spikes'];
        $queryParams = array_intersect_key($this->params()->fromQuery(), array_flip($queryParamsNames));
        $savedQuery = array_diff_key($this->params()->fromQuery(), array_flip($queryParamsNames));
        foreach ($queryParams as $name => $val)
            if ($val == null)
                unset($queryParams[$name]);
        
        if (count($queryParams)){
            $this->redirect()->toRoute('home/tyres/search', $queryParams, ['query' => $savedQuery]);
        }
        
        $view = new ViewModel();
        $form = new \Tyres\Form\Search();
        //@TODO cache
        $tyreTable = $this->getServiceLocator()->get('TyresModelTyreTable');
        foreach (['width', 'height', 'diameter'] as $prop){
            $props = $tyreTable->getOneProperty($prop);
            $form->get($prop)->setValueOptions(array_combine($props, $props));
        }
        $brandTable = $this->getServiceLocator()->get('TyresModelBrandTable');
        $brands = $brandTable->getBrands();
        $keyVal = [];
        foreach ($brands as $brand) 
            $keyVal[$brand->name] = $brand->name;
        $form->get('brandName')->setValueOptions($keyVal);
        
        $formSend = count(array_intersect_key($this->params()->fromRoute(), array_flip($queryParamsNames))) > 0;
        if ($formSend) {
            //загрузка моделей бренда
            if ($this->params()->fromRoute('brandName') != null) {
                $brandName = $this->params()->fromRoute('brandName');
                $modelTable = $this->getServiceLocator()->get('TyresModelModelTable');
                foreach ($brands as $brand){
                    if ($brand->name == $brandName){
                        $models = $modelTable->getModels(['brandId' => $brand->id]);
                        $keyVal = [];
                        foreach ($models as $model) 
                            $keyVal[$model->name] = $model->name;
                        $form->get('modelName')->setValueOptions($keyVal);
                        break;
                    }
                }
            }
            
            $form->setData($this->params()->fromRoute());
            if ($form->isValid()){
                $tyres = $tyreTable->getTyres($form->getData());
                if ($tyres->count()) {
                    $modelIds = [];
                    $tyreIds = [];
                    foreach ($tyres as $tyre) {
                        $modelIds[$tyre->modelId] = $tyre->modelId;
                        $tyreIds[$tyre->id] = $tyre->id;
                    }

                    $prices = $this->getServiceLocator()->get('TyresModelPriceTable')->getPrices(['tyreIds' => $tyreIds]);
                    $providers = $this->getServiceLocator()->get('TyresModelProviderTable')->getProviders();

                    $pricesWithProviders = [];
                    foreach ($prices as $price){
                        foreach ($providers as $provider)
                            if ($price->providerId == $provider->id){
                                $price->setProvider($provider);
                                break;
                            }
                        $pricesWithProviders[] = $price;
                    }
                    unset($providers, $prices);

                    $tyresWithPrices = [];
                    foreach ($tyres as $tyre) {
                        foreach ($pricesWithProviders as $price)
                            if ($price->tyreId == $tyre->id)
                                $tyre->addPrice($price);
                        $tyresWithPrices[] = $tyre;
                    }
                    unset($pricesWithProviders, $tyres);
                    $models = $this->getServiceLocator()->get('TyresModelModelTable')->getModels(['ids' => $modelIds]);

                    $brandIds = [];
                    $modelsWithTyres = [];
                    foreach ($models as $model){
                        $brandIds[$model->brandId] = $model->brandId;
                        foreach ($tyresWithPrices as $tyre)
                            if ($tyre->modelId == $model->id)
                                $model->addTyre($tyre);
                        $modelsWithTyres[$model->id] = $model;
                    }
                    unset($tyresWithPrices, $models);


                    $brands = $brandTable->getBrands(['ids' => $brandIds]);

                    $brandsWithModels = [];
                    foreach ($brands as $key => $brand){
                        foreach ($modelsWithTyres as $model)
                            if ($model->brandId == $brand->id)
                                $brand->addModel($model);
                        $brandsWithModels[$brand->id] = $brand;
                    }
                    unset($modelsWithTyres, $brands);

                    $view->setVariable('brands', $brandsWithModels);
                } else {
                    $view->setVariable('isFoundTyres', false);
                }
            }
        }
        
        $view->setVariable('form', $form);
        
        $brandTable = $this->getServiceLocator()->get('TyresModelBrandTable');
        $popBrands = $brandTable->getBrands(['tyresCountLoad' => true, 'order' => 'tyresCount', 'way' => false, 'pagination' => true, ]);
        $view->setVariable('popBrands', $popBrands);
        
        return $view;
    }
    
    public function tyreAction(){
        $view = new ViewModel();
        
        $brand = $this->getServiceLocator()->get('TyresModelBrandTable')->getBrands(['name' => $this->params()->fromRoute('brandName')])->current();
        
        $model = $this->getServiceLocator()->get('TyresModelModelTable')->getModels(['name' => $this->params()->fromRoute('modelName'), 'brandId' => $brand->id, ])->current();
        
        $view->setVariables([
            'brand' => $brand,
            'model' => $model,
        ]);

        $tyreTable = $this->getServiceLocator()->get('TyresModelTyreTable');
        $tyres = $tyreTable->getTyres($this->params()->fromRoute());
        
        $size = null;
        if ($this->params()->fromRoute('width') != null)
            $size = array_combine(
                ['width', 'height', 'diameter'], 
                [ $this->params()->fromRoute('width'), $this->params()->fromRoute('height'), $this->params()->fromRoute('diameter') ]
            );
        $this->NavigationTree($brand, $model, $size);
        
        if ($tyres->count()) {
            $tyreIds = [];
            foreach ($tyres as $tyre) 
                $tyreIds[$tyre->id] = $tyre->id;

            $prices = $this->getServiceLocator()->get('TyresModelPriceTable')->getPrices(['tyreIds' => $tyreIds]);
            $providers = $this->getServiceLocator()->get('TyresModelProviderTable')->getProviders();

            $pricesWithProviders = [];
            foreach ($prices as $price){
                foreach ($providers as $provider)
                    if ($price->providerId == $provider->id){
                        $price->setProvider($provider);
                        break;
                    }
                $pricesWithProviders[] = $price;
            }
            unset($providers, $prices);

            $tyresWithPrices = [];
            foreach ($tyres as $tyre) {
                foreach ($pricesWithProviders as $price)
                    if ($price->tyreId == $tyre->id)
                        $tyre->addPrice($price);
                $tyresWithPrices[] = $tyre;
            }
            unset($pricesWithProviders, $tyres);

            $view->setVariable('tyres', $tyresWithPrices);
        } else {
            $view->setVariable('isFoundTyres', false);
        }
        
        return $view;
    }
    
    public function getbrandmodelsAction() {
        $view = new ViewModel();
        
        $brandTable = $this->getServiceLocator()->get('TyresModelBrandTable');
        $brandName = $this->params()->fromPost('brandName');
        
        $brand = $brandTable->getBrands(['name' => $brandName])->current();
        
        $modelTable = $this->getServiceLocator()->get('TyresModelModelTable');
        $models = $modelTable->getModels(['brandId' => $brand->id]);
        
        $view->setVariables(array(
            'models' => $models,
            'isXmlHttpRequest' => $this->getRequest()->isXmlHttpRequest(),
        ));
        
        if ($this->getRequest()->isXmlHttpRequest())
            $view->setTerminal(true);
        
        return $view;
    }
    

}
