<?php
namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Auth\Form\AdminLogin;
use Auth\Utility\UserPassword;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        $loginForm = new AdminLogin('loginForm');
        if ($this->getRequest()->isPost()) {
            $loginForm->setData($this->getRequest()->getPost());
            if ($loginForm->isValid()) {
                $userPassword = new UserPassword();
                $encyptPass = $userPassword->create($loginForm->get('password')->getValue());
                
                $authService = $this->getServiceLocator()->get('AdminAuthService');
                $authService->getAdapter()
                    ->setIdentity($loginForm->get('email')->getValue())
                    ->setCredential($encyptPass);
                $result = $authService->authenticate();
                if ($result->isValid()) {
                    $sysUserTable = $this->getServiceLocator()->get('AuthModelSystemUserTable');
                    $user = $sysUserTable->getUserByEmail($authService->getIdentity());
                    unset($user->password);
                    $authService->getStorage()->write($user);
                     
                    $this->flashMessenger()->addMessage(array(
                        'success' => 'Login Success.'
                    ));
                } else {
                    $this->flashMessenger()->addMessage(array(
                        'error' => 'invalid credentials.'
                    ));
                }
                return $this->redirect()->toRoute('admin');
            }
        }
        
        $view = new ViewModel();
        $view->setVariable('loginForm', $loginForm);
        //отключить лайоут
        //$view->setTerminal(true);
        return $view;
    }

    public function logoutAction(){
        $authService = $this->getServiceLocator()->get('AdminAuthService');
        $authService->clearIdentity();
        return $this->redirect()->toRoute('auth/admin/login');
    }
    
    public function resetpassAction()
    {
        //@TODO
    }
    
}