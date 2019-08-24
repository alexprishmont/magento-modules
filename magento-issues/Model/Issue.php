<?php

namespace Alexpr\IssuesHandler\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class Issue extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'issues_list';

    protected $_cacheTag = 'issues_list';

    protected $_eventPrefix = 'issues_list';

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];
        return $values;
    }

    protected function _construct()
    {
        $this->_init('Alexpr\IssuesHandler\Model\ResourceModel\Issue');
    }
}