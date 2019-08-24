<?php
declare(strict_types=1);

namespace Alexpr\SimpleShipping\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;

class Config extends AbstractHelper
{
    protected $storeManager;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    public function getApiUserId()
    {
        return (int)$this->getConfigData('apiuserid');
    }

    private function getConfigData($field)
    {
        $path = 'carriers/alexpr_simpleshipping/' . $field;

        return $this->scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()
        );
    }

    public function getApiAuthUri()
    {
        return (string)$this->getConfigData('apiauth');
    }

    public function getApiUri()
    {
        return (string)$this->getConfigData('apiuri');
    }

    public function getRoundRule()
    {
        $rule = $this->getConfigData('roundrule');
        if (!$rule) {
            return null;
        }
        return $rule;
    }

    public function isLoggerEnabled()
    {
        return (bool)$this->getConfigData('loggerenable');
    }

    public function getStoreId()
    {
        return (string)$this->getConfigData('apistoreid');
    }

    public function getFreeShippingStatusForProvider(string $provider)
    {
        $provider = str_replace('_', ' ', $provider);
        $map = $this->getMap();
        foreach ($map as $item) {
            if (array_search($provider, $item)) {
                if (!isset($item['free_shipping_applicable'])) {
                    break;
                }
                return (bool)$item['free_shipping_applicable'];
            }
        }
        return true;
    }

    public function getMap()
    {
        $data = json_decode($this->getConfigData('mapping'), true);
        if (!$data) {
            return [];
        }
        return $data;
    }
}