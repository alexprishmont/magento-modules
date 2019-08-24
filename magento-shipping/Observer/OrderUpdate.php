<?php

namespace Alexpr\SimpleShipping\Observer;

use Alexpr\SimpleShipping\Helper\ApiProvider;
use Alexpr\SimpleShipping\Helper\Config;
use Alexpr\SimpleShipping\Logger\CustomLogger;
use Alexpr\SimpleShipping\Model\ApiOrderFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class OrderUpdate implements ObserverInterface
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

        $data = $order->getData();
        $status = $data['status'];
        $items = $order->getAllItems();

        $apiOrder = $this->apiOrderFactory->create()
            ->load($orderId, 'order_id');

        $apiOrderData = $apiOrder->getData();
        if (isset($apiOrderData['api_order_id'])) {
            $apiOrderId = $apiOrderData['api_order_id'];

            $body = [
                'status' => $status,
                'items' => [
                    $this->constrainItemsArray($items)
                ]
            ];

            $response = $this->api->updateOrder($apiOrderId, $body);

            if ($this->config->isLoggerEnabled()) {
                $this->logger->log('Order [order id: ' . $orderId . '] status changed to: ' . $status);
                $this->logger->log('Updating in API...');
                $this->logger->log('Response: ' . $response);
            }
        }
    }

    protected function constrainItemsArray(array $items)
    {
        $resultItems = [];
        foreach ($items as $item) {
            $resultItems[] = [
                'name' => $item->getName(),
                'price' => $item->getPrice(),
                'qty' => $item->getQtyOrdered()
            ];
        }
        return $resultItems;
    }
}