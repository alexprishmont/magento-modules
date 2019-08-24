<?php
namespace Alexpr\IssuesHandler\Logger;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

class CustomLogger
{
    private $logger;

    public function __construct(Logger $logger)
    {
        $writer = new Stream(BP . '/var/log/issues_reporting.log');
        $this->logger = $logger;
        $this->logger->addWriter($writer);
    }

    public function log(string $message)
    {
        $this->logger->debug($message);
    }
}