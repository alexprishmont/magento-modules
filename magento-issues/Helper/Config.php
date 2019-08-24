<?php

namespace Alexpr\IssuesHandler\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Config extends AbstractHelper
{
    const XML_PATH_FEATURED = 'Alexpr/';
    const SECONDS = 3600;


    public function getStatuses()
    {
        return $this->getGeneralConfig('statuses');
    }

    public function getSecondsUntilStatusChange()
    {
        return $this->getGeneralConfig('time_to_status_change') * self::SECONDS;
    }

    private function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(
            self::XML_PATH_FEATURED . 'general/' . $code,
            $storeId
        );
    }

    private function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig
            ->getValue(
                $field,
                ScopeInterface::SCOPE_STORE,
                $storeId
            );
    }
}
