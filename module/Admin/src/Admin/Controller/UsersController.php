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
    
    public function editAction() {
        $usersTable = $this->getServiceLocator()->get('AuthModelUserTable');

        $form = new \Auth\Form\User();
        
        $userId = $this->params()->fromRoute('id');
        $user = $usersTable->getUser($userId);
        $form->setData($user->getArrayCopy());
        
        if ($this->params()->fromPost('saveUser') != null) {
            $form->setInputFilter($user->getInputFilter('edit-user'));
            $post = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                ['id' => $userId]    
            );
            $form->setData($post);
            
            if ($form->isValid()) {
                //$user->exchangeArray($form->getData());
                $user->discount = $form->get('discount')->getValue();
                
                $usersTable->saveUser($user);
                
                $this->FlashMessenger()->addSuccessMessage('Данные пользователя сохранены.');
                
                $referer = $this->referer();
                if ($referer->getRouteName() != null) 
                    return $this->redirect()->toRoute($referer->getRouteName(), $referer->getParams());
                else
                    return $this->redirect()->toRoute('admin/default', array('controller' => 'users', 'action' => 'index'));
            }
        }
        
        return new ViewModel(array(
            'form' => $form,
            )
        );
    }
}
