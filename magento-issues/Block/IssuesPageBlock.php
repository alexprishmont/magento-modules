<?php
namespace Alexpr\IssuesHandler\Block;

use Magento\Framework\View\Element\Template;

class IssuesPageBlock extends Template
{
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getFormAction()
    {
        return '/issue/index/post';
    }

    public function nameTextInputPlaceholder()
    {
        return __('Your name');
    }

    public function emailTextInputPlaceholder()
    {
        return __('Your email');
    }

    public function issueTextAreaPlaceholder()
    {
        return __('Write about issue, conditions how it appears');
    }

    public function reportButtonText()
    {
        return __('Report');
    }

    public function headerText()
    {
        return __('Report your found issue!');
    }
}