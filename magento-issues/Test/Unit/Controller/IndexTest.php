<?php

namespace Alexpr\IssuesHandler\Test\Unit\Controller;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    private $resultFactoryMock;

    private $pageMock;

    private $objectManager;

    private $indexModel;

    private $adminIndexModel;

    private $configMock;

    private $titleMock;

    public function testIndexIndexController()
    {
        $this->resultFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->pageMock);

        $this->pageMock->expects($this->once())
            ->method('getConfig')
            ->willReturn($this->configMock);

        $this->configMock->expects($this->once())
            ->method('getTitle')
            ->willReturn($this->titleMock);

        $this->assertSame($this->pageMock, $this->indexModel->execute());
    }

    public function testAdminIndexController()
    {
        $this->resultFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->pageMock);

        $this->pageMock->expects($this->once())
            ->method('getConfig')
            ->willReturn($this->configMock);

        $this->configMock->expects($this->once())
            ->method('getTitle')
            ->willReturn($this->titleMock);

        $this->assertSame($this->pageMock, $this->adminIndexModel->execute());
    }

    protected function setUp()
    {
        $this->pageMock = $this->getMockBuilder('Magento\Framework\View\Result\Page')
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultFactoryMock = $this->getMockBuilder('Magento\Framework\View\Result\PageFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->configMock = $this->getMockBuilder('Magento\Framework\View\Page\Config')
            ->disableOriginalConstructor()
            ->getMock();

        $this->titleMock = $this->getMockBuilder('Magento\Framework\View\Page\Title')
            ->disableOriginalConstructor()
            ->getMock();

        $this->objectManager = new ObjectManager($this);

        $this->indexModel = $this->objectManager->getObject(
            'Alexpr\IssuesHandler\Controller\Index\Index',
            ['resultPageFactory' => $this->resultFactoryMock]
        );

        $this->adminIndexModel = $this->objectManager->getObject(
            'Alexpr\IssuesHandler\Controller\Adminhtml\Issues\Index',
            ['resultPageFactory' => $this->resultFactoryMock]
        );
    }

    protected function tearDown()
    {
        $this->pageMock = null;
        $this->resultFactoryMock = null;
        $this->objectManager = null;
        $this->indexModel = null;
        $this->adminIndexModel = null;
        $this->configMock = null;
        $this->titleMock = null;
    }
}
