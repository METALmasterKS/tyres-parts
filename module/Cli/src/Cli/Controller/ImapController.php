<?php

namespace Cli\Controller;

use Zend\Mvc\Controller\AbstractActionController;

use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;

class ImapController extends AbstractActionController {
    
    public function indexAction() {
        $mail = new \Zend\Mail\Storage\Pop3(array(
            'host'     => 'pop.mail.ru',
            'port'     => 995,
            'ssl'      => 'SSL',
            'user'     => 'PartsPostavka@bk.ru',
            'password' => 'postavka2016'
        ));

        echo $mail->countMessages() . " messages found\n";
        foreach ($mail as $message) {
            if ($message->hasFlag(\Zend\Mail\Storage::FLAG_SEEN)) {
                continue;
            }
            // mark recent/new mails
            if ($message->hasFlag(\Zend\Mail\Storage::FLAG_RECENT)) {
                echo 'recent - ';
            } else {
                echo 'new - ';
                echo "Mail from '{$message->from}': {$message->subject}\n";
                echo "Multipart: ".$message->isMultipart()."\n";
            }
        }

    }
    
    

}
