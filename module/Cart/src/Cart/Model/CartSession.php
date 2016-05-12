<?php

namespace Cart\Model;

class CartSession extends AbstractCart
{
    const SESSION_KEY = 'CART';

    private $tyres = array();
    private $session;
    
    public function __sleep()
    {
        $this->session = null;
        $this->tyres = null;
        return ['id', 'date_created', 'date_modify', 'items'];
    }

    public function setSesion($session)
    {
        $this->session = $session;
    }

    public function setTyres($tyres) 
    {
        $this->tyres = $tyres;
    }

    
    public function addItem($addingItem)
    {
        parent::addItem($addingItem);
        $this->save();
    }
    
        
    public function deleteItem($type, $objectId)
    {
        parent::deleteItem($type, $objectId);
        $this->save();
    }

    
    public function addTyre($tyre) 
    {
        $this->tyres[$tyre->id] = $tyre;
        $item = $this->getItemByObjectId('tyres', $tyre->id);
        if ($item != null) 
            $item->setObject($tyre);
    }
    
    public function save()
    {
        $this->date_modify = time();
        $this->session->__set(self::SESSION_KEY, $this);
    }
    
    public function createItem($type, $objectId, $count)
    {
        $itemSession = new ItemSession();
        $itemSession->id = uniqid();
        $itemSession->cart_id = $this->id;
        $itemSession->object_type = $type;
        $itemSession->object_id = $objectId;
        $itemSession->count = $count;
        
        return $itemSession;
    }

}
