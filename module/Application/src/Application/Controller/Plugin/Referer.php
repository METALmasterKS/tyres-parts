<?php

namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

use Zend\Mvc\Router\RouteMatch;

class Referer extends AbstractPlugin
{
    
    private $route;
    private $params;

    public function __invoke() {
        
        $referer = $this->getController()->getRequest()->getHeader('Referer')->getUri();
        $request = clone $this->getController()->getRequest();
        $request->setUri($referer);

        $match = $this->getController()->getServiceLocator()->get('Router')->match($request);
        if ($match instanceof RouteMatch) {
            $this->route = $match->getMatchedRouteName();
            $this->params = $match->getParams();
        }
        
        return $this;
    }
    
    public function getRouteName(){
        return $this->route;
    }
    
    public function getParams(){
        return $this->params;
    }
    
    public function getParam($name) {
        return isset($this->params[$name]) ? $this->params[$name] : null;
    }

}
