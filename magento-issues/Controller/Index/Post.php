<?php

namespace Alexpr\IssuesHandler\Controller\Index;

use Alexpr\IssuesHandler\Helper\InputValidator;
use Alexpr\IssuesHandler\Logger\CustomLogger;
use Alexpr\IssuesHandler\Model\IssueFactory as IssueModelFactory;
use Alexpr\IssuesHandler\Model\ResourceModel\IssueFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Message\ManagerInterface;

class Post extends Action
{
    const WRONG_INPUT_MESSAGE = 'Wrong input data.';
    const SUCCESS_INPUT_MESSAGE = 'Your issue successfully submitted.';
    const LOG_ISSUE_CREATION_MESSAGE = 'New issue created. Issue data: ';

    const PATH_TO_REDIRECT = 'issue';

    protected $messageManager;
    protected $validator;
    protected $resource;
    protected $logger;
    protected $issueModelFactory;

    public function __construct(
        Context $context,
        ManagerInterface $messageManager,
        InputValidator $validator,
        IssueFactory $resource,
        CustomLogger $logger,
        IssueModelFactory $issueModelFactory
    ) {
        $this->messageManager = $messageManager;
        $this->validator = $validator;
        $this->resource = $resource->create();
        $this->logger = $logger;
        $this->issueModelFactory = $issueModelFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $post = (array)$this->getRequest()->getPost();

        if (!empty($post)) {
            if (!$this->validator->validate($post)) {
                $this->messageManager->addErrorMessage(__(self::WRONG_INPUT_MESSAGE));
                return $this->_redirect(self::PATH_TO_REDIRECT);
            }
            $data = [
                'sender_name' => $post['name'],
                'sender_email' => $post['email'],
                'issue' => $post['issue_text']
            ];

            $newIssue = $this->issueModelFactory->create();
            $newIssue->addData($data);
            $this->resource->save($newIssue);

            $this->messageManager->addSuccessMessage(__(self::SUCCESS_INPUT_MESSAGE));

            $this->logger->log(
                self::LOG_ISSUE_CREATION_MESSAGE . json_encode($data)
            );
            return $this->_redirect(self::PATH_TO_REDIRECT);
        }

        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}
