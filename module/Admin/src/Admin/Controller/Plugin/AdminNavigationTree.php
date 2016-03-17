<?php

namespace Admin\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class AdminNavigationTree extends AbstractPlugin
{
    private $brand;
    private $model;
    private $size;
    
    private $navigation;


    public function __invoke($brand = null, $model = null, $size = null) {
        $this->navigation = $this->getController()->getServiceLocator()->get('navigation');
        
        $this->brand = $brand;
        $this->model = $model;
        $this->size = $size;

        if ($this->brand instanceof \Tyres\Model\Brand)
            $this->loadBrandNavigation();
        
        return $this;
    }
    
    private function loadBrandNavigation() {
        $page = $this->navigation->findOneBy('id', 'admin-tyres-brands');
        if ($page instanceof \Zend\Navigation\Page\Mvc){
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
            $page->setActive(true);
        }
    }
    
    public function models()
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
    
    public function addModel() {
        $page = $this->navigation->findOneBy('id', 'admin-tyre-brand-models-' . $this->brand->id); //($group->parentId == \Catalog\Model\Group::ROOT ? $group->id : $group->parentId));
        if ($page instanceof \Zend\Navigation\Page\Mvc) {
            $page->addPage([
                'label' => 'Добавление модели бренда "' . $this->brand->name . '"',
                'id' => 'admin-tyre-brand-models-add-' . $this->brand->id,
                'route' => 'admin/default',
                'params' => [
                    'controller' => 'models',
                    'action' => 'add',
                    'id' => $this->brand->id,
                ],
            ]);
            $page = $this->navigation->findOneBy('id', 'admin-tyre-brand-models-add-' . $this->brand->id);
            $page->setActive(true);
        }
        return $this;
    }
    
    public function editModel() {
        $page = $this->navigation->findOneBy('id', 'admin-tyre-brand-models-' . $this->brand->id);
        if ($page instanceof \Zend\Navigation\Page\Mvc) {
            $page->addPage([
                'label' => 'Редактирование модели "' . $this->model->name . '"',
                'id' => 'admin-tyre-brand-models-edit-' . $this->model->id,
                'route' => 'admin/default',
                'params' => [
                    'controller' => 'models',
                    'action' => 'edit',
                    'id' => $this->model->id,
                ],
            ]);
            $page = $this->navigation->findOneBy('id', 'admin-tyre-brand-models-edit-' . $this->model->id);
            $page->setActive(true);
        }
        return $this;
    }
    


}
