<?php

namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Auth\Form\UserLogin;
use Auth\Utility\UserPassword;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\Validator\Authentication as AuthenticationValidator;
use Zend\Authentication\Storage\Chain as Chain;
use Zend\Authentication\Storage\Session as Session;
use Zend\Session\Container;
use Auth\Model\User;

class UserController extends AbstractActionController
{

    public function indexAction()
    {
        $authService = $this->getServiceLocator()->get('UserAuthService');
        if ($authService->hasIdentity())
            return $this->redirect()->toRoute('account');
        
        $form = new UserLogin('loginForm');

        if ($this->getRequest()->isPost()) {
            $form->getInputFilter()->add([ 'name' => 'email', 'required' => true, 'filters' => array( array('name' => 'StripTags'), array('name' => 'StringTrim')), ]);
            $form->getInputFilter()->add([ 'name' => 'password', 'required' => true, 'filters' => array( array('name' => 'StripTags'), array('name' => 'StringTrim')), ]);
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $authService = $this->getServiceLocator()->get('UserAuthService');
                
                $authService->getAdapter()
                    ->setIdentity($form->get('email')->getValue())
                    ->setCredential(sha1($form->get('password')->getValue()));
                $result = $authService->authenticate();
                if ($result->isValid()) {
                    $user = $this->getServiceLocator()->get('AuthModelUserTable')->getUserByEmail($authService->getIdentity());
                    $user->date_last_login = time();
                    $user->ip_last_login = $this->getRequest()->getServer('REMOTE_ADDR');
                    
                    $this->getServiceLocator()->get('AuthModelUserTable')->saveUser($user);
                    unset($user->password);
                    $authService->getStorage()->write($user);

                    $this->flashMessenger()->addSuccessMessage('Вы успешно вошли в свой Личный кабинет');
                    return $this->redirect()->toRoute('account');
                } else {
                    $this->flashMessenger()->addErrorMessage('Неверно введен логин или пароль.');
                }
            }
        }

        $view = new ViewModel();
        $view->setVariable('form', $form);
        //отключить лайоут
        //$view->setTerminal(true);
        return $view;
    }
    
    public function registrationAction()
    {
        $authService = $this->getServiceLocator()->get('UserAuthService');
        if ($authService->hasIdentity())
            return $this->redirect()->toRoute('account');
        
        $form = new \Auth\Form\UserRegistration();
        if ($this->getRequest()->isPost()) {
            $user = new \Auth\Model\User($this->getServiceLocator());
            $form->setInputFilter($user->getInputFilter('registration'));
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $user->exchangeArray($form->getData());
                $user->password = sha1($form->get('password')->getValue());
                $user->date_register = time();
                $user->ip_register = $this->getRequest()->getServer('REMOTE_ADDR');
                
                if ($this->getServiceLocator()->get('AuthModelUserTable')->saveUser($user))
                {
                    $smtp = $this->getServiceLocator()->get('MailSMTP');
                    //адреса менеджеров
                    $config = $this->getServiceLocator()->get('config');
                    $MAILcfg = $config['settings']['mail'];
                    //письмо для менеджеров
                    $mail = new \Mail\Message();
                    $mail->setBody("Зарегистрирован новый пользователь ".implode("\n", [$form->get('email')->getValue(), $form->get('password')->getValue()]));
                    $mail->addTo($MAILcfg['managers']);
                    $mail->setSubject('Регистрация нового пользователя на сайте');
                    $smtp->send($mail);
                    sleep(3);
                    //письмо для пользователей
                    $mail = new \Mail\Message();
                    $mail->setBody(implode("\n", [$form->get('email')->getValue(), $form->get('password')->getValue()]));
                    $mail->addTo($form->get('email')->getValue());
                    $mail->setSubject('Регистрация на сайте '.$this->getRequest()->getServer('HOST'));
                    $smtp->send($mail);
                    
                    $this->FlashMessenger()->addSuccessMessage('Вы зарегистрированны, проверьте вашу почту для того чтобы подтвердить адрес электронной почты.');
                    return $this->redirect()->toRoute('auth/default', array('controller' => 'user', 'action' => 'login'));
                } else {
                    $this->FlashMessenger()->addErrorMessage('Не удалось зарегистировать новый аккаунт.'); 
                }
            }
        }
        $view = new ViewModel();
        $view->setVariable('form', $form);

        return $view;
    }

    public function resetpasswordAction()
    {
        $authService = $this->getServiceLocator()->get('UserAuthService');
        if ($authService->hasIdentity())
            return $this->redirect()->toRoute('account');
        
        $form = new \Auth\Form\UserResetPassword();
        if ($this->getRequest()->isPost()) {
            $form->getInputFilter()->get('email')->getValidatorChain()->addByName('Db\RecordExists', array(
                'table'   => 'users',
                'field'   => 'email',
                'adapter' => $this->getServiceLocator()->get('DBMaster'),
                'messages' => array( 
                    \Zend\Validator\Db\AbstractDb::ERROR_NO_RECORD_FOUND => 'Не существует пользователя с данным адресом электронной почты.', 
                ),
            ));
            
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $email = $form->get('email')->getValue();
                if ($login == null && $email == null) {
                    $this->FlashMessenger()->addErrorMessage('Одно из полей должно быть заполнено.'); 
                } else {
                    if ($login != null) {
                        $user = $this->getServiceLocator()->get('AuthModelUserTable')->getUserByName($login);
                    } elseif($email != null) {
                        $users = $this->getServiceLocator()->get('AuthModelUserTable')->getUsers(['email'=>$email]);
                        $user = $users->current();
                    }
                    
                    $rndPass = new \Auth\Utility\RandomPassword();
                    $newPassword = $rndPass->getRandomPassword();
                    $user->password = sha1($newPassword);
                    $this->getServiceLocator()->get('AuthModelUserTable')->saveUser($user);
                    
                    //письмо 
                    $smtp = $this->getServiceLocator()->get('MailSMTP');
                    $mail = new \Mail\Message();
                    $mail->setBody(implode("\n", [$user->email, $newPassword]));
                    $mail->addTo($user->email);
                    $mail->setSubject('Восстановление доступа к личному кабинету сайта '.$this->getRequest()->getServer('HOST'));
                    $smtp->send($mail);
                    
                    $this->FlashMessenger()->addSuccessMessage('Новый пароль отправлен на вашу почту.');
                    return $this->redirect()->toRoute('auth/default', ['controller'=>'user', 'action'=>'index']);
                    
                }
            } 
        }
        
        
        return new ViewModel([
            'form' => $form,
        ]);
    }


    public function logoutAction()
    {
        $authService = $this->getServiceLocator()->get('UserAuthService');
        $authService->clearIdentity();
        
        $referer = $this->referer();
        if ($referer->getRouteName() != null) 
            return $this->redirect()->toRoute($referer->getRouteName(), $referer->getParams());
        else
            return $this->redirect()->toRoute('auth/user/login');
        
    }

}
