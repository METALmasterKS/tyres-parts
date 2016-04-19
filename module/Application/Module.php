<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        \Zend\Navigation\Page\Mvc::setDefaultRouter ($e->getRouter());
        
        \Zend\Validator\AbstractValidator::setDefaultTranslator($e->getApplication()->getServiceManager()->get('Translator'));
        
        \Locale::setDefault('ru_RU');
        
        $this->getSettings($e->getApplication());
        
        $e->getApplication()->getEventManager()->attach(MvcEvent::EVENT_RENDER, array($this, 'setFeedbackForm'), 100);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getSettings($application){
        $config = $application->getServiceManager()->get('config');
        $settings = $config['settings'];
        $viewModel = $application->getMvcEvent()->getViewModel();
        $viewModel->settings = $settings;
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function setFeedbackForm($e){
        if ($e->getRequest()->isXmlHttpRequest()) 
            return;
        
        $form = new \Application\Form\Feedback();
        if ($e->getRequest()->getPost('feedback') != null) {
            $form->setData($e->getRequest()->getPost());
            if ($form->isValid()){
                
                $this->autoRegistration($e, $form);
                
                $body = sprintf("Фамилия Имя: %s\n"
                    . "Почта: %s\n"
                    . "Телефон: %s\n"
                    . "Сообщение: \n%s\n", 
                        $form->get('name')->getValue(), 
                        $form->get('email')->getValue(), 
                        $form->get('phone')->getValue(), 
                        $form->get('message')->getValue());
                
                if ($form->get('theme')->getValue() == "Запчасти") {
                    $body .= sprintf("Автомобиль: %s %s %s\n%s\n", 
                        $form->get('car-brand')->getValue(), 
                        $form->get('car-model')->getValue(), 
                        $form->get('car-year')->getValue(), 
                        $form->get('car-vin')->getValue());
                }
                
                $smtp = $e->getApplication()->getServiceManager()->get('MailSMTP');
                //адреса менеджеров
                $config = $e->getApplication()->getServiceManager()->get('config');
                $MAILcfg = $config['settings']['mail'];
                //письмо для менеджеров
                $mail = new \Mail\Message();
                $mail->addReplyTo($form->get('email')->getValue(), $form->get('name')->getValue());
                $mail->setBody($body);
                $mail->addTo($MAILcfg['managers']);
                $mail->setSubject('Вопрос с сайта, тема - '.$form->get('theme')->getValue());
                $smtp->send($mail);
                
                $flashMessenger = $e->getApplication()->getServiceManager()->get('ControllerPluginManager')->get('FlashMessenger');
                $flashMessenger->addSuccessMessage('Ваш вопрос отравлен.');
                
                $redirect = $e->getApplication()->getServiceManager()->get('ControllerPluginManager')->get('redirect');
                $redirect->toUrl($e->getRequest()->getUri()->setFragment('feedback'));
            }
        }
        
        $viewModel = $e->getViewModel();
        $viewModel->setVariables(array(
            'feedbackForm' => $form,
        ));
    }
    
    public function autoRegistration($e, $form) {
        $validator = new \Zend\Validator\Db\NoRecordExists(array(
            'table'   => 'users',
            'field'   => 'email',
            'adapter' => $e->getApplication()->getServiceManager()->get('Zend\Db\Adapter\Adapter'),
        ));

        if ($validator->isValid($form->get('email')->getValue())) {
            $user = new \Auth\Model\User();
            $user->exchangeArray($form->getData());
            $passGen = new \Auth\Utility\RandomPassword();
            $password = $passGen->getRandomPassword();
            $user->password = sha1($password);
            $user->date_register = time();
            $user->ip_register = $e->getRequest()->getServer('REMOTE_ADDR');
            if ($e->getApplication()->getServiceManager()->get('AuthModelUserTable')->saveUser($user)) {
                $smtp = $e->getApplication()->getServiceManager()->get('MailSMTP');

                $mail = new \Mail\Message();
                $mail->setBody(implode("\n", [$form->get('email')->getValue(), $password]));
                $mail->addTo($form->get('email')->getValue());
                $mail->setSubject('Регистрация на сайте '.$e->getRequest()->getServer('HOST'));
                $smtp->send($mail);
            }
        }    
    }
}
