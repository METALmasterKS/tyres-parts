<?php

namespace Cart\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as Session;

use Cart\Model\Cart;
use Cart\Model\CartSession;

class StorageCartFactory implements FactoryInterface
{
    private $serviceLocator;


    public function createService(ServiceLocatorInterface $serviceLocator)
    {   
        $this->serviceLocator = $serviceLocator;
        
        $cart = $this->getSessionCart();
        if ($cart === false) 
            $cart = $this->createSessionCart();
        
        return $cart;
    }
    
    private function getSessionCart() {
        $session = $this->serviceLocator->get('commonData');
        if ($session->__isset(CartSession::SESSION_KEY)) {
            $cart = $session->__get(CartSession::SESSION_KEY);
            $cart->setSesion($session);
            return $cart;
        }
        return false;
    }
    
    private function createSessionCart() {
        $session = $this->serviceLocator->get('commonData');
        $cart = new CartSession();
        $cart->id = uniqid();
        $cart->date_created = $cart->date_modify = time();
        $cart->setSesion($session);
        $cart->save();
        return $cart;
    }
}
