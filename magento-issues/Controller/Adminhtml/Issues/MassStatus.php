<?php

namespace Alexpr\IssuesHandler\Controller\Adminhtml\Issues;

use Alexpr\IssuesHandler\Model\ResourceModel\Issue\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;

class MassStatus extends Action
{
    protected $filter;

    protected $collectionFactory;

    public function __construct(
        Action\Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        if ($this->getRequest()->getActionName() === 'massStatus') {
            $data = $this->getRequest()->getParams();
            $status = $data['status'];

            foreach ($collection as $item) {
                $item->setStatus($status)->save();
            }

            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) have been updated.', $collectionSize)
            );

            return $resultRedirect->setPath('*/*/');
        }
        return $resultRedirect->setPath('*/*/');
    }
}
