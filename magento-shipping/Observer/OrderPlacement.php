<?php

namespace Alexpr\SimpleShipping\Observer;

use Alexpr\SimpleShipping\Helper\ApiProvider;
use Alexpr\SimpleShipping\Helper\Config;
use Alexpr\SimpleShipping\Logger\CustomLogger;
use Alexpr\SimpleShipping\Model\ApiOrderFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class OrderPlacement implements ObserverInterface
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

        $orderData = $order->getData();
        $address = $order->getShippingAddress()->getData();
        $items = $order->getAllItems();

        $response = $this->api->placeOrder(
            $this->constrainPostArray($address, $orderData, $items)
        );

        $response = json_decode($response, true);

        $addedOrderId = $response['id'];

        $newOrder = $this->apiOrderFactory->create();
        $newOrder->addData([
            'order_id' => $order->getId(),
            'api_order_id' => $addedOrderId
        ]);

        $newOrder->save();

        if ($this->config->isLoggerEnabled()) {
            $this->logger->log(
                'Create order response: ' . json_encode($response)
            );

            $this->logger->log(
                'Data for creation: ' . json_encode($this->constrainPostArray($address, $orderData, $items))
            );
        }
    }

    protected function constrainPostArray(array $address, array $orderData, array $items)
    {
        return [
            'createdAt' => $orderData['created_at'],
            'customerName' => $this->constrainCustomerFullname($orderData),
            'status' => $orderData['status'],
            'address' => [
                'city' => $address['city'],
                'country' => $address['country_id'],
                'postCode' => $address['postcode'],
                'street' => $address['street']
            ],
            'items' => [
                $this->constrainItemsArray($items)
            ],
            'shippingMethod' => $orderData['shipping_method']
        ];
    }

    protected function constrainCustomerFullname(array $orderData)
    {
        return $orderData['customer_firstname'] .
            ' ' .
            (isset($orderData['customer_middlename']) ? $orderData['customer_middlename'] . ' ' : '') .
            $orderData['customer_lastname'];
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
