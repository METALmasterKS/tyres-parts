<?php

namespace Admin\Controller\Plugin;

class ModelImageCreator extends ImageCreatorAbstract
{
    private $model;
    private $modelImageFileName;


    public function __invoke($model) {
        parent::__invoke($model);
        $this->model = $model;
        return $this;
    }
    
    public function setModel(\Tyres\Model\Model $model) {
        $this->model = $model;
        return $this;
    }
    
    public function updateImageFile($postImage){
        if (!$this->model instanceof \Tyres\Model\Model)
            return false;
        
        $upload = new \upload($postImage);
        if ($success = $upload->uploaded) {
            // сохраняем оригинальный файл 
            $upload->process($this->IMGcfg['dir'].$this->IMGcfg['tyres'].$this->IMGcfg['models']);
            $success &= $upload->processed;
            $this->modelImageFileName = $upload->file_dst_name;
            $this->savedFilePaths[] = $upload->file_dst_pathname;
            $upload->clean();
        }
        
        return $success;
    }


    public function getImageFileName(){
        return $this->modelImageFileName;
    }
    
    public function removeImages($imagesNames = null){
        $paths = [
            $this->IMGcfg['dir'].$this->IMGcfg['tyres'].$this->IMGcfg['models'],
        ];
        $images = array();
        if (is_string ($imagesNames) && $imagesNames != null)
            $images = [$imagesNames];
        elseif (is_array($imagesNames))
            $images = $imagesNames;
        elseif ($this->model instanceof \Tyres\Model\model)
            $images = $this->model->getImages();
        foreach ($images as $image)
            $this->remove($paths, $image);
    }
    
}
