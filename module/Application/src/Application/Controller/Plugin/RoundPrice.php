<?php

namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class RoundPrice extends AbstractPlugin
{
    private $sourcePrice;
    
    public function __invoke($val) {
        return round(floatval($val), (floatval($val) < 100 ? 2 : 0));
    }
    
}
