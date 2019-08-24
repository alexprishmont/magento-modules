<?php

namespace Alexpr\IssuesHandler\Model\ResourceModel\Issue;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'issues_list_collection';
    protected $_eventObject = 'issues_list_collection';

    protected function _construct()
    {
        $this->_init(
            'Alexpr\IssuesHandler\Model\Issue',
            'Alexpr\IssuesHandler\Model\ResourceModel\Issue'
        );
    }
}