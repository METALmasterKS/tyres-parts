<?php

namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class NavigationTree extends AbstractPlugin
{
    private $brand;
    private $model;
    private $size;
    
    private $navigation;
    private $isAdmin;


    public function __invoke($brand = null, $model = null, $size = null) {
        $this->navigation = $this->getController()->getServiceLocator()->get('navigation');
        
        $this->isAdmin = false;
        
        $this->brand = $brand;
        $this->model = $model;
        $this->size = $size;

        if ($this->brand instanceof \Tyres\Model\Brand)
            $this->loadBrandNavigation();
        
        if ($this->model instanceof \Tyres\Model\Model)
            $this->loadModelNavigation(); //Подгрузка навигации модели покрышки
        
        if (!empty($this->size))
            $this->loadSizeNavigation(); //Подгрузка навигации размерности
        
        return $this;
    }
    
    private function loadBrandNavigation() {
        $page = $this->navigation->findOneBy('id', $this->isAdmin ? 'admin-tyres-brands' : 'home-tyres');
        if ($page instanceof \Zend\Navigation\Page\Mvc){
            if ($this->isAdmin) {
                $page->addPage([
                    'label' => $this->brand->name,
                    'id' => 'admin-tyre-brand-'.$this->brand->id,
                    'route' => 'admin/default',
                    'params' => [
                        'controller' => 'brands',
                        'action' => 'index',
                        'sub' => 'search',
                    ],
                    'query' => [
                        'search'=>'Поиск',
                        'name' => $this->brand->name,
                    ], ]);
                $page = $this->navigation->findOneBy('id', 'admin-tyre-brand-'.$this->brand->id);
            } else {
                $page->addPage([
                    'label' => $this->brand->name,
                    'id' => 'tyre-brand-'.$this->brand->id,
                    'route' => 'home/tyres/brands/brand',
                    'params' => [
                        'name' => $this->brand->name,
                    ]]);
                $page = $this->navigation->findOneBy('id', 'tyre-brand-'.$this->brand->id);
            }
            $page->setActive(true);
        }
    }
    
    private function loadModelNavigation() {
        $page = $this->navigation->findOneBy('id', 'tyre-brand-'.$this->brand->id);
        if ($page instanceof \Zend\Navigation\Page\Mvc){
            $page->addPage([
                'label' => $this->model->name,
                'id' => 'tyre-model-'.$this->model->id,
                'route' => 'home/tyres/tyre',
                'params' => [
                    'brandName' => $this->brand->name,
                    'modelName' => $this->model->name,
                ]]);
            $page = $this->navigation->findOneBy('id', 'tyre-model-'.$this->model->id);
            $page->setActive(true);
        }
    }

    private function loadSizeNavigation() {
        $page = $this->navigation->findOneBy('id', 'tyre-model-'.$this->model->id);
        if ($page instanceof \Zend\Navigation\Page\Mvc){
            $page->addPage([
                'label' => sprintf('%s/%sR%s', $this->size['width'], $this->size['height'], $this->size['diameter']),
                'id' => 'tyre-model-size',
                'route' => 'home/tyres/tyre',
                'params' => [
                    'brandName' => $this->brand->name,
                    'modelName' => $this->model->name,
                    'width' => $this->size['width'],
                    'height' => $this->size['height'],
                    'diameter' => $this->size['diameter'],
                ]]);
            $page = $this->navigation->findOneBy('id', 'tyre-model-size');
            $page->setActive(true);
        }
    }
    
    public function adminModels()
    {
        $page = $this->navigation->findOneBy('id', 'admin-tyre-brand-' . $this->brand->id);
        if ($page instanceof \Zend\Navigation\Page\Mvc) {
            $page->addPage([
                'label' => 'Модели бренда "' . $this->brand->name . '"',
                'id' => 'admin-tyre-brand-models-' . $this->brand->id,
                'route' => 'admin/default',
                'params' => [
                    'controller' => 'models',
                    'action' => 'index',
                    'id' => $this->brand->id,
                ],
            ]);
            $page = $this->navigation->findOneBy('id', 'admin-tyre-brand-models-' . $this->brand->id);
            $page->setActive(true);
        }
        return $this;
    }
    
    
    
    private function translit($val){
        $translit = $this->getController()->getServiceLocator()->get('viewhelpermanager')->get('translit');
        return $translit($val);
    }

}
