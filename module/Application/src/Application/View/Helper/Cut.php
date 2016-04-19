<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Cut extends AbstractHelper {

    private $cutPos;
    private $text;
    private $cut;

    public function __invoke($text) {
        $pattern = "/<div id=\"cut\">.*<\/div>/iu";
        preg_match($pattern, $text, $matches, PREG_OFFSET_CAPTURE);
        
        $this->cut = $text;
        if (isset($matches[0][1])) {
            $this->cutPos = $matches[0][1];
            $this->cut = substr($text, 0, $this->cutPos);
        }
        
        $this->text = preg_replace($pattern, '', $text);

        return $this;
    }
    
    public function getText(){
        return $this->text;
    }
    
    public function getCut(){
        return $this->cut;
    }

}
