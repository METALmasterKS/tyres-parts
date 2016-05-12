<?php

namespace Cart\Model;

interface CartInterface
{
    
    public function getItemByObjectId($type, $objectId);

    public function addItem($addingItem);

    public function getItems($type);

}