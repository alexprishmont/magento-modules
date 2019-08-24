<?php
declare(strict_types=1);

namespace Alexpr\FeaturedProducts\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_FEATURED = 'featuredproducts/';

    public function isModuleEnabled()
    {
        return $this->getGeneralConfig('enable');
    }

    public function getModuleTitle()
    {
        return $this->getGeneralConfig('title');
    }

    public function getCollectionLimit()
    {
        return $this->getGeneralConfig('limit');
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

    private function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(
            self::XML_PATH_FEATURED . 'general/' . $code,
            $storeId
        );
    }
}