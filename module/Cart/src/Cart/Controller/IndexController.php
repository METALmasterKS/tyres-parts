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
        $view = new ViewModel(array( 'cart' => $cart, ));
        
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
        
        $config = $this->getServiceLocator()->get('config');
        $IMG = $config['settings']['images'];
        
        $count = $this->getRequest()->getPost('count');
        $objectId = $this->getRequest()->getPost('objectId');
        $type = $this->getRequest()->getPost('type');
        
        $cart = $this->getServiceLocator()->get('Cart');
        
        if ($type == 'tyres') {
            $tyrePrice = $this->getServiceLocator()->get('TyresModelPriceTable')->getPrice($objectId);
            $tyre = $this->getServiceLocator()->get('TyresModelTyreTable')->getTyre($tyrePrice->tyreId);
            $model = $this->getServiceLocator()->get('TyresModelModelTable')->getModel($tyre->modelId);
            $brand = $this->getServiceLocator()->get('TyresModelBrandTable')->getBrand($model->brandId);
            
            $name = sprintf("%s %s %s/%sR%s", $brand->name, $model->name, $tyre->width, $tyre->height, $tyre->diameter);
            $url = $this->url()->fromRoute('home/tyres/tyre', ['brandName'=>$brand->name, 'modelName'=>$model->name, 'width' => $tyre->width, 'height' => $tyre->height, 'diameter'=>$tyre->diameter]);
            $images = $model->getImages();
            $firstImage = current($images);
            $image = ($firstImage != null) ? implode('', [$IMG['host'], $IMG['tyres'], $IMG['models'], $firstImage]) : '';
            
            $addItem = $cart->createItem('tyres', $tyrePrice->id, $count, $tyrePrice->getPrice(), $name, $url, $image);
            $cart->addItem($addItem);
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
        if ($this->getRequest()->getPost('count')) {
            $cart = $this->getServiceLocator()->get('Cart');

            $counts = $this->getRequest()->getPost('count');
            foreach ($counts as $id => $count) {
                $item = $cart->getItemById($id);
                $item->count = $count;
            }
            $cart->save();
        }
        return $this->redirect()->toRoute('home/cart/default', array('controller' => 'index', 'action' => 'index'));
    }
    
    public function removefromcartAction()
    {
        $itemId = $this->params()->fromRoute('id');
        
        $cart = $this->getServiceLocator()->get('Cart');
        $cart->deleteItemById($itemId);
        
        $cart->save();
        
        $referer = $this->referer();
        if ($referer->getRouteName() != null) 
            return $this->redirect()->toRoute($referer->getRouteName(), $referer->getParams());
        else
            return $this->redirect()->toRoute('home/cart/default', array('controller' => 'index', 'action' => 'index'));

    }
    
    public function orderAction() {
        $form = new \Auth\Form\UserRegistration();
        //заполнение полей если залогинен
        
        if ($this->getRequest()->isPost()) {
            $newUser = new \Auth\Model\User();
            $form->setInputFilter($newUser->getInputFilter('send-order'));
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $userTable = $this->getServiceLocator()->get('AuthModelUserTable');
                $user = $userTable->getUserByEmail($form->get('email')->getValue());
                if ($user instanceof \Auth\Model\User){
                    $this->sendOrder($user);
                } else { //регистрируем
                    $newUser->exchangeArray($form->getData());
                    $rndPass = new \Auth\Utility\RandomPassword();
                    $password = $rndPass->getRandomPassword();
                    $newUser->password = sha1($password);
                    $newUser->date_register = time();
                    $newUser->ip_register = $this->getRequest()->getServer('REMOTE_ADDR');
                    
                    if ($userTable->saveUser($newUser))
                        $this->sendOrder($newUser, $password);
                }
            } 
        }
        
        $view = new ViewModel(['form' => $form]);
        return $view;
    }
    
    private function sendOrder($user, $password = null){
        $orderBody = '<table>';
        
        $cart = $this->getServiceLocator()->get('Cart');
        foreach ($cart->getItems() as $item){
            $orderBody .= sprintf("<tr> <td>%s</td> <td>%s</td> <a href='%s%s'>%s</a> <td>%s руб.</td>  <td>%s шт.</td> <td>%s руб.</td> </tr>", 
                $item->object_id, 
                $item->type, 
                $this->getRequest()->getServer('HOST'), 
                $item->url, $item->name, $item->price, $item->count, $item->getSumm());
        }
        $orderBody .= '</table>';
        
        $managerText = sprintf("Пользователь сделал заказ,\nemail: %s,\ntel: %s\n", $user->email, $user->phone);
        $userText = sprintf("Спасибо за Ваш заказ! Наш менеджер обязательно свяжится с вами.\n");
        
        $smtp = $this->getServiceLocator()->get('MailSMTP');
        //адреса менеджеров
        $config = $this->getServiceLocator()->get('config');
        $MAILcfg = $config['settings']['mail'];
        //письмо для менеджеров
        $mail = new \Mail\Message();
        $mail->addTextPart($managerText)
            ->addHtmlPart($orderBody)
            ->addTo($MAILcfg['managers'])
            ->setSubject('Заказ пользователя на сайте');
        $smtp->send($mail);
        sleep(3);

        //письмо для пользователей
        $mail = new \Mail\Message();
        $mail->addTextPart($userText)->addHtmlPart($orderBody);
        if ($password != null)
            $mail->addTextPart(sprintf("Для входа в личный кабинет используйте \nЛогин: %s\nПароль: %s\n", $user->email, $password));
        $mail->addTo($user->email);
        $mail->setSubject('Заказ на сайте '.$this->getRequest()->getServer('HOST'));
        $smtp->send($mail);
        
        $cart->clear();
        
        $this->FlashMessenger()->addSuccessMessage('Спасибо за Ваш заказ! Наш менеджер обязательно свяжится с вами.');
        return $this->redirect()->toRoute('home/cart/default', array('controller' => 'index', 'action' => 'success'));
    }
    
    public function successAction(){
        
    }


}
