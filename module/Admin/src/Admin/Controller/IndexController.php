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

use Zend\Authentication\AuthenticationService;

class IndexController extends AbstractActionController {

    public function indexAction() {
        
        $authService = $this->getServiceLocator()->get('AdminAuthService');

        if ($authService->hasIdentity()) {
            // Идентификатор подлинности существует, получить его
            $identity = $authService->getIdentity();
            $user = $this->getServiceLocator()->get('AuthModelSystemUserTable')->getUser($identity->id);
        }
        
        return new ViewModel(array(
            
        ));
    }

}
