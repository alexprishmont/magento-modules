<?php

namespace Alexpr\SimpleShipping\Observer;

use Alexpr\SimpleShipping\Helper\ApiProvider;
use Alexpr\SimpleShipping\Helper\Config;
use Alexpr\SimpleShipping\Logger\CustomLogger;
use Alexpr\SimpleShipping\Model\ApiOrderFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class OrderDelete implements ObserverInterface
{
    private $api;
    private $config;
    private $apiOrderFactory;
    private $logger;

    public function __construct(
        Config $config,
        ApiProvider $api,
        ApiOrderFactory $apiOrderFactory,
        CustomLogger $logger
    ) {
        $this->config = $config;
        $this->api = $api;
        $this->apiOrderFactory = $apiOrderFactory;
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $orderId = $order->getId();

        $loadedOrder = $this->apiOrderFactory
            ->create()
            ->load($orderId, 'order_id');

        if ($loadedOrder) {
            $loadedOrderData = $loadedOrder->getData();

            $response = $this->api->deleteOrder(
                $loadedOrderData['api_order_id']
            );
            $loadedOrder->delete();

            if ($this->config->isLoggerEnabled()) {
                $this->logger->log('Trying to delete from API [web order id]: ' . $orderId);
                $this->logger->log('API Response: ' . $response);
                $this->logger->log(
                    'Deletion successfull. Deleted id: ' . $loadedOrderData['api_order_id']
                );
            }
        }
    }
}
