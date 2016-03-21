<?php

namespace Admin\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

abstract class ImageCreatorAbstract extends AbstractPlugin
{
    protected $IMGcfg;
    protected $savedFilePaths;
    protected $object;

    public function __invoke($object) {
        
        $config = $this->getController()->getServiceLocator()->get('config');
        $this->IMGcfg = $config['settings']['images'];
        
        $this->object = $object;

        return $this;
    }
    
    protected function removeAllSavedFiles() {
        foreach ($this->savedFilePaths as $file_pathname)
            if (file_exists($file_pathname))
                unlink($file_pathname);
    }
    
    protected function remove($paths, $filename) {
        if (is_array($paths) && $filename != null) {
            foreach ($paths as $path) {
                $filepath = rtrim($path, '/').'/'.$filename;
                if (file_exists($filepath))
                    unlink($filepath);
            }
        }
    }

}
