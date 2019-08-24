<?php
declare(strict_types=1);

namespace Alexpr\IssuesHandler\Helper;

use Zend\Validator\EmailAddress;

class InputValidator
{
    private $emailValidator;

    public function __construct(EmailAddress $emailValidator)
    {
        $this->emailValidator = $emailValidator;
    }

    public function validate(array $post): bool
    {
        if (empty($post['name']) || empty($post['email']) || empty($post['issue_text'])) {
            return false;
        }

        if (!$this->emailValidator->isValid($post['email'])) {
            return false;
        }
        return true;
    }
}