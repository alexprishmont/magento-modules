<?php
declare(strict_types=1);

namespace Alexpr\IssuesHandler\Cron;

use Alexpr\IssuesHandler\Helper\Config;
use Alexpr\IssuesHandler\Logger\CustomLogger;
use Alexpr\IssuesHandler\Model\ResourceModel\Issue\CollectionFactory;
use DateTime;
use Exception;

class StatusChange
{
    private $logger;

    private $collectionFactory;

    private $config;

    public function __construct(
        CustomLogger $logger,
        CollectionFactory $issueFactory,
        Config $config
    ) {
        $this->logger = $logger;
        $this->collectionFactory = $issueFactory;
        $this->config = $config;
    }

    public function execute(): void
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('status', ['nin' => ['resolved', 'urgent']]);

        try {
            foreach ($collection as $issue) {
                $data = $issue->getData();
                $timestampDiff = $this->getTimestampDifference(
                    $this->getNowTimestamp(),
                    $this->getIssueCreationTimestamp($data['created_at'])
                );
                $timestampUntilUrgent = $this->config->getSecondsUntilStatusChange();

                if ($timestampDiff >= $timestampUntilUrgent) {
                    $issue->setStatus('urgent');
                    $this->logger->log(
                        'Issue ID: ' . $data['entity_id'] . ' status changed to "Urgent" as it not resolved.'
                    );
                    continue;
                }
            }

            $collection->save();
            $this->logger->log('Cron job is done.');
        } catch (Exception $e) {
            $this->logger->log('Error while cron is running: ' . $e->getMessage());
        }
    }

    private function getTimestampDifference(int $timestamp1, int $timestamp2): int
    {
        return $timestamp1 - $timestamp2;
    }

    private function getNowTimestamp(): int
    {
        $nowDate = new DateTime('now');
        return $nowDate->getTimestamp();
    }

    private function getIssueCreationTimestamp(string $date): int
    {
        $issueDate = new DateTime($date);
        return $issueDate->getTimestamp();
    }
}
