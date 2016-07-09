<?php

namespace Mestrona\CategoryRedirect\Observer;

use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class FixAttributes implements ObserverInterface
{
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /**
         * @var $collection Collection
         */
        $collection = $observer->getEvent()->getCategoryCollection();
        $collection->addAttributeToSelect('redirect_url');
    }
}
