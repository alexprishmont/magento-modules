<?php
declare(strict_types=1);

namespace Alexpr\PromoLabel\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_FEATURED = 'promolabel/';

    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig
            ->getValue(
                $field,
                ScopeInterface::SCOPE_STORE,
                $storeId
            );
    }

    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(
            self::XML_PATH_FEATURED . 'general/' . $code,
            $storeId
        );
    }
}