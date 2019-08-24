<?php

namespace Alexpr\IssuesHandler\Ui\Component\Listing\MassActions;

use Alexpr\IssuesHandler\Helper\Config;
use Magento\Framework\UrlInterface;
use Zend\Stdlib\JsonSerializable;

class DynamicAction implements JsonSerializable
{
    private $config;

    private $urlBuilder;

    private $options;

    private $urlPath;

    private $paramName;

    private $additionalData = [];

    private $data;

    public function __construct(
        Config $config,
        UrlInterface $urlBuilder,
        array $data = []
    ) {
        $this->config = $config;
        $this->urlBuilder = $urlBuilder;
        $this->data = $data;
    }

    public function jsonSerialize()
    {
        if ($this->options === null) {
            $statuses = json_decode($this->config->getStatuses(), true);
            $options = $this->setDefaultOptions();

            foreach ($statuses as $key => $status) {
                if (array_search($status, $options) !== false) {
                    continue;
                }

                $options[$key]['value'] = strtolower($status['status']);
                $options[$key]['label'] = $status['status'];
            }
            $this->prepareData();
            foreach ($options as $optionCode) {
                $this->options[$optionCode['value']] = [
                    'type' => 'status_' . $optionCode['value'],
                    'label' => $optionCode['label'],
                ];

                if ($this->urlPath && $this->paramName) {
                    $this->options[$optionCode['value']]['url'] = $this->urlBuilder->getUrl(
                        $this->urlPath,
                        [$this->paramName => $optionCode['value']]
                    );
                }

                $this->options[$optionCode['value']] = array_merge_recursive(
                    $this->options[$optionCode['value']],
                    $this->additionalData
                );
            }
            $this->options = array_values($this->options);
        }
        return $this->options;
    }

    private function setDefaultOptions()
    {
        return [
            'resolved' => [
                'value' => 'resolved',
                'label' => 'Resolved'
            ],
            'scheduled' => [
                'value' => 'scheduled',
                'label' => 'Scheduled'
            ],
            'pending' => [
                'value' => 'pending',
                'label' => 'Pending'
            ],
        ];
    }

    private function prepareData()
    {
        foreach ($this->data as $key => $value) {
            switch ($key) {
                case 'urlPath':
                    $this->urlPath = $value;
                    break;
                case 'paramName':
                    $this->paramName = $value;
                    break;
                default:
                    $this->additionalData[$key] = $value;
                    break;
            }
        }
    }
}
