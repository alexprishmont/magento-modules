<?php
namespace Alexpr\SimpleShipping\Model\ResourceModel\ApiOrder;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'api_orders_ids_collection';
    protected $_eventObject = 'api_orders_collection';

    protected function _construct()
    {
        $this->_init('Alexpr\SimpleShipping\Model\ApiOrder', 'Alexpr\SimpleShipping\Model\ResourceModel\ApiOrder');
    }
}