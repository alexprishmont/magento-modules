<?php
declare(strict_types=1);

namespace Alexpr\SimpleShipping\Model\Carrier;

use Alexpr\SimpleShipping\Helper\ApiProvider;
use Alexpr\SimpleShipping\Helper\Config;
use Alexpr\SimpleShipping\Helper\NamesHelper;
use Alexpr\SimpleShipping\Helper\PriceHelper;
use Alexpr\SimpleShipping\Logger\CustomLogger;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class SimpleCarrier extends AbstractCarrier implements CarrierInterface
{
    const FREE_SHIPPING_COST = 0;

    protected $_code = 'alexpr_simpleshipping';
    protected $apiHelper;
    protected $_isFixed = true;
    protected $rateResultFactory;
    protected $rateMethodFactory;
    protected $country;
    protected $storeManager;
    protected $priceHelper;
    protected $namesHelper;
    protected $config;
    protected $ruleRepository;
    protected $customLogger;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        ResultFactory $rateResultFactory,
        MethodFactory $rateMethodFactory,
        ApiProvider $provider,
        StoreManagerInterface $storeManager,
        PriceHelper $priceHelper,
        NamesHelper $namesHelper,
        Config $config,
        CustomLogger $customLogger,
        array $data = []
    ) {
        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->apiHelper = $provider;
        $this->storeManager = $storeManager;
        $this->priceHelper = $priceHelper;
        $this->namesHelper = $namesHelper;
        $this->config = $config;
        $this->customLogger = $customLogger;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    public function collectRates(RateRequest $request)
    {
        if (!$this->isActive()) {
            return false;
        }

        $this->country = $request->getDestCountryId();
        $result = $this->rateResultFactory->create();
        $method = $this->rateMethodFactory->create();

        $countryShipmentDetails = $this->apiHelper->getCountryShipmentDetails(
            $this->country
        );

        $method->setCarrier($this->getCarrierCode());
        $method->setCarrierTitle(
            $this->namesHelper->normalizeName($countryShipmentDetails['carierName'])
        );

        $method->setMethod(strtolower($countryShipmentDetails['methodName']));
        $method->setMethodTitle(
            $this->namesHelper->normalizeName($countryShipmentDetails['methodName'])
        );

        $convertedPrice = $this->getPrice($request, $countryShipmentDetails);
        $method->setPrice($convertedPrice);
        $method->setCost($convertedPrice);
        $result->append($method);

        if ($this->config->isLoggerEnabled()) {
            $this->customLogger->log(
                'Carrier API: '
            );

            $this->customLogger->log(
                'Shipment details from API: ' . json_encode($countryShipmentDetails)
            );

            $this->customLogger->log(
                'Converted price: ' . $convertedPrice
            );
        }

        return $result;
    }

    private function getPrice(
        RateRequest $request,
        array $shipmentDetails
    ) {
        if ($request->getFreeShipping() &&
            $this->config->getFreeShippingStatusForProvider($shipmentDetails['carierName'])
        ) {
            return self::FREE_SHIPPING_COST;
        }

        return $this->priceHelper
            ->convertPrice(
                $shipmentDetails['price'],
                $shipmentDetails['currency'],
                $this->storeManager->getStore()->getCurrentCurrencyCode(),
                $this->config->getRoundRule()
            );
    }

    public function getAllowedMethods()
    {
        return [$this->getCarrierCode() => __($this->getMethodName())];
    }

    private function getMethodName()
    {
        $details = $this->apiHelper
            ->getCountryShipmentDetails(
                $this->country
            );
        return $details['methodName'];
    }
}
