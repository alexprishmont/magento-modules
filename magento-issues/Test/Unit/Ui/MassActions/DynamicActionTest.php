<?php

namespace Alexpr\IssuesHandler\Test\Unit\Ui\MassActions;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class DynamicActionTest extends TestCase
{
    private $objectManager;
    private $config;
    private $urlBuilder;
    private $class;

    public function testSerialize()
    {
        $this->config->expects($this->any())
            ->method('getStatuses')
            ->willReturn('{}');

        $result[] = [
            'type' => 'status_resolved',
            'label' => 'Resolved',
            'url' => null,
            'confirm' => [
                'title' => 'Issues status',
                'message' => 'Are you sure to change current status to selected for issue?'
            ]
        ];

        $result[] = [
            'type' => 'status_scheduled',
            'label' => 'Scheduled',
            'url' => null,
            'confirm' => [
                'title' => 'Issues status',
                'message' => 'Are you sure to change current status to selected for issue?'
            ]
        ];

        $result[] = [
            'type' => 'status_pending',
            'label' => 'Pending',
            'url' => null,
            'confirm' => [
                'title' => 'Issues status',
                'message' => 'Are you sure to change current status to selected for issue?'
            ]
        ];

        $this->assertEquals($result, $this->class->jsonSerialize());
    }

    protected function setUp()
    {
        $this->config = $this->getMockBuilder('Alexpr\IssuesHandler\Helper\Config')
            ->disableOriginalConstructor()
            ->getMock();

        $this->urlBuilder = $this->getMockBuilder('Magento\Framework\UrlInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->objectManager = new ObjectManager($this);
        $this->class = $this->objectManager->getObject(
            'Alexpr\IssuesHandler\Ui\Component\Listing\MassActions\DynamicAction',
            [
                'config' => $this->config,
                'urlBuilder' => $this->urlBuilder,
                'data' => [
                    'urlPath' => 'issues/issues/massStatus',
                    'paramName' => 'status',
                    'confirm' => [
                        'title' => 'Issues status',
                        'message' => 'Are you sure to change current status to selected for issue?'
                    ],
                ],
            ]
        );
    }

    protected function tearDown()
    {
        $this->objectManager = null;
        $this->config = null;
        $this->urlBuilder = null;
        $this->class = null;
    }
}
