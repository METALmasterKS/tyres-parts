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

    public function setSession($session)
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
    
    public function clear()
    {
        unset($this->session->{self::SESSION_KEY});
    }
    
    public function createItem($type, $objectId, $count, $price, $name, $url, $image)
    {
        $itemSession = new ItemSession();
        $itemSession->id = uniqid();
        $itemSession->cart_id = $this->id;
        $itemSession->date_added = time();
        $itemSession->object_type = $itemSession->type = $type;
        $itemSession->object_id = $objectId;
        $itemSession->count = $count;
        $itemSession->price = $price;
        $itemSession->name = $name;
        $itemSession->url = $url;
        $itemSession->image = $image;
        
        return $itemSession;
    }

}
