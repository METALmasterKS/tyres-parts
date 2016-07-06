<?php

namespace Cart\Model;

abstract class AbstractCart implements CartInterface
{

    public $id;
    public $date_created;
    public $date_modify;
    public $items = array();

    public function getItemById($id){
        return (isset($this->items[$id]) ? $this->items[$id] : null);
    }

    public function getItemByObjectId($type, $objectId)
    {
        foreach ($this->getItems($type) as $id => $item)
            if ($item->object_id == $objectId)
                return isset($this->items[$id]) ? $this->items[$id] : null;
    }

    public function addItem($addingItem)
    {
        $item = $this->getItemByObjectId($addingItem->object_type, $addingItem->object_id);
        if ($item != null) {
            $item->count = ($item->count + $addingItem->count);
            $item->price = $addingItem->price;
            $item->date_added = time();
        } else {
            $this->items[$addingItem->id] = $addingItem;
        }
    }
    
    public function deleteItemById($id){
        if (isset($this->items[$id])) 
            unset($this->items[$id]);
            
        return $this;
    }
        
    public function deleteItem($type, $objectId)
    {
        foreach ($this->getItems($type) as $id => $item)
            if ($item->variant_id == $objectId)
                if (isset($this->items[$id]))
                    unset($this->items[$id]);
    }

    
    public function getObjectIds($type){
        $ids = array();
        foreach ($this->getItems($type) as $item) {
            $ids[] = $item->object_id;
        }
        return $ids;
    }
    
    public function getItems($type = null)
    {   
        $typeItems = [];
        if ($type != null){
            foreach ($this->items as $item)
                if ($item->object_type == $type)
                    $typeItems[] = $item;
            return $typeItems;
        }
        return $this->items;
    }
    
    public function getTotalCount()
    {
        $count = 0;
        $items = $this->getItems();
        foreach ($items as $item) {
            $count += $item->getCount();
        }

        return (float) round($count, 2);
    }
    
    public function getTotalSumm($round = 2, $with_vat = false)
    {
        $sum = 0;
        $items = $this->getItems();
        foreach ($items as $item) {
            $sum += ($item->getPrice() * $item->count);
        }

        return (float) round($sum, (int) $round);
    }

    

}
