<?php
declare(strict_types=1);

namespace Alexpr\FeaturedProducts\Controller\Index;

use Alexpr\FeaturedProducts\Helper\Data;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $resultPageFactory;

    protected $helper;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Data $helper
    ) {
        $this->helper = $helper;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $helper = $this->helper;
        if ($helper->isModuleEnabled()) {
            $resultPage = $this->resultPageFactory->create();
            $resultPage->getConfig()
                ->getTitle()
                ->set(
                    (__($helper->getModuleTitle()))
                );
            return $resultPage;
        }
        return $this->_redirect('/');
    }
}
