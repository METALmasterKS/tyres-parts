<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CKEditorController extends AbstractActionController
{
    public function uploadAction()
    {
        
        // Required: anonymous function reference number as explained above.
        $funcNum = $_GET['CKEditorFuncNum'];
        // Optional: instance name (might be used to load a specific configuration file or anything else).
        $CKEditor = $_GET['CKEditor'];
        // Optional: might be used to provide localized messages.
        $langCode = $_GET['langCode'];
        // Optional: compare it with the value of `ckCsrfToken` sent in a cookie to protect your server side uploader against CSRF.
        // Available since CKEditor 4.5.6.
        $token = $_POST['ckCsrfToken'];

        // Check the $_FILES array and save the file. Assign the correct path to a variable ($url).
        
        $config = $this->getServiceLocator()->get('config');
        $this->IMGcfg = $config['settings']['images'];
        
        $upload = new \upload($_FILES['upload']);
        if ($success = $upload->uploaded) {
            // сохраняем оригинальный файл 
            $upload->process($this->IMGcfg['dir'].$this->IMGcfg['upload-images']);
            $success &= $upload->processed;
            $filename = $upload->file_dst_name;
            $filepath = $upload->file_dst_pathname;
            $upload->clean();
        }
        
        if ($success) {
            $url = $this->IMGcfg['host'].$this->IMGcfg['upload-images'].$filename;
            $message = 'Изображение загружено';
        } else {
            $url = '';
            $message = 'Ошибка! Не удалось загрузить изображение';
        }
        
        $view = new ViewModel(['funcNum' => $funcNum, 'url' => $url, 'message' => $message]);
        $view->setTerminal(true);
        return $view;
    }
    
    public function browseAction() {
        $view = new ViewModel();
        $view->setTerminal(true);
        return $view;
    }
    
}
