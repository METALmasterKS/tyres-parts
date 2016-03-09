<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Catalog\Paginator;

class MorePaginator extends \Zend\Paginator\Paginator
{
    
    protected $currentLastId = null;

    /**
     * Returns the items for the current page.
     *
     * @return Traversable
     */
    public function getCurrentItems()
    {
        if ($this->currentItems === null) {
            $this->currentItems = $this->getItemsByLastId($this->getCurrentLastId());
        }

        return $this->currentItems;
    }
    
    public function getCurrentLastId()
    {
        return $this->currentLastId;
    }
    
    public function setCurrentLastId($lastId)
    {
        $this->currentLastId = $lastId;
        $this->currentItems      = null;
        $this->currentItemCount  = null;

        return $this;
    }
    
    /**
     * Returns the items starts from last id.
     *
     * @param int $lastId
     * @return mixed
     */
    public function getItemsByLastId($lastId)
    {
        $items = $this->adapter->getItems($lastId, $this->getItemCountPerPage());

        $filter = $this->getFilter();

        if ($filter !== null) {
            $items = $filter->filter($items);
        }

        if (!$items instanceof Traversable) {
            $items = new \ArrayIterator($items);
        }

        return $items;
    }
    
    public function getItem($itemNumber, $lastId = null)
    {
        if ($lastId === null) {
            $lastId = $this->getCurrentLastId();
        } 

        $page = $this->getItemsByLastId($lastId);
        $itemCount = $this->getItemCount($page);

        if ($itemCount == 0) {
            return null;
            throw new \Zend\Paginator\Exception\InvalidArgumentException('Page from last id ' . $lastId . ' does not exist');
        }

        if ($itemNumber < 0) {
            $itemNumber = ($itemCount + 1) + $itemNumber;
        }

        $itemNumber = $this->normalizeItemNumber($itemNumber);

        if ($itemNumber > $itemCount) {
            return null;
        }

        return $page[$itemNumber - 1];
    }

}
