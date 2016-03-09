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

class UsersController extends AbstractActionController
{
    public function indexAction() {

        $data = ['pagination' => true];
        $form = new \Admin\Form\SearchUsersForm();
        if ($this->params()->fromQuery('search') != null) {
            $form->setData($this->params()->fromQuery());
            if ($form->isValid()){
                foreach ($form->getData() as $key => $value) {//только те параметры которые заполнены
                    if ($value != null)
                        $data[$key] = is_integer(strpos($key,'date_')) ? strtotime($value) : $value;
                }
            }
        }
        
        $users = $this->getServiceLocator()->get('AuthModelUserTable')->getUsers($data);
        if ($users instanceof \Zend\Paginator\Paginator)
            $users->setCurrentPageNumber((int) $this->params()->fromRoute('page'));
        
        return new ViewModel(array(
            'users' => $users,
            'form' => $form,
            'query' => $this->params()->fromQuery(),
            )
        );
    }
}
