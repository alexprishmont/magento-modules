<?php
declare(strict_types=1);

namespace Alexpr\SimpleShipping\Helper;

use Alexpr\SimpleShipping\Logger\CustomLogger;
use Magento\Store\Model\StoreManagerInterface;

class ApiProvider
{
    private $storeManager;

    private $client;

    private $logger;

    private $config;

    public function __construct(
        Config $config,
        StoreManagerInterface $storeManager,
        CustomLogger $logger
    ) {
        $this->storeManager = $storeManager;
        $this->client = curl_init();
        $this->logger = $logger;
        $this->config = $config;
    }

    public function __destruct()
    {
        curl_close($this->client);
    }

    public function updateOrder(string $orderId, array $body = [])
    {
        if (!empty($body)) {
            $response = $this->processApiRequest(
                $this->config->getApiUri() . '/' . $this->config->getStoreId() . '/' . $orderId,
                'PUT',
                $body
            );
            return $response;
        }
        return false;
    }

    private function processApiRequest(
        string $uri,
        string $method,
        array $body = []
    ) {
        $options = [
            CURLOPT_URL => $uri,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => (!empty($body) ? json_encode($body) : ''),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-type: application/json'],
        ];

        curl_setopt_array($this->client, $options);
        $result = curl_exec($this->client);
        return $result;
    }

    public function getCountryShipmentDetails(
        string $countryCode
    ): array {
        return json_decode(
            $this->processApiRequest(
                $this->config->getApiUri() . '/' . $this->getApiAuthToken() . '/' . $countryCode,
                'GET'
            ),
            true
        );
    }

    public function getApiAuthToken(): string
    {
        $response = $this->processApiRequest(
            $this->config->getApiAuthUri() . '/' . $this->config->getApiUserId(),
            'GET'
        );
        $response = json_decode($response, true);
        return $response['authToken'];
    }

    public function placeOrder(array $data)
    {
        return $this->processApiRequest(
            $this->config->getApiUri() .
            '/' .
            $this->config->getStoreId(),
            'POST',
            $data
        );
    }

    public function deleteOrder(string $orderId)
    {
        return $this->processApiRequest(
            $this->config->getApiUri() . '/' . $this->config->getStoreId() . '/' . $orderId,
            'DELETE'
        );
    }
}
