<?php
/**
 * ZF2 Mail Manager
 *
 * @link        https://github.com/ripaclub/zf2-mailman
 * @copyright   Copyright (c) 2014, RipaClub
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Mail\Service;

use Mail\Message;

/**
 * Interface MailInterface
 *
 * @package MailMan\Service
 */
interface MailInterface
{
    /**
     * @param Message $message
     */
    public function send(Message $message);

    /**
     * @return mixed
     */
    public function getAdditionalInfo();

    /**
     * @param array $additionalInfo
     * @return mixed
     */
    public function setAdditionalInfo(array $additionalInfo);
}
