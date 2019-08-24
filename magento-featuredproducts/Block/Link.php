<?php
declare(strict_types=1);

namespace Alexpr\FeaturedProducts\Block;

class Link extends \Magento\Framework\View\Element\Html\Link
{
    protected function __toHtml()
    {
        if ($this->getTemplate() !== false) {
            return parent::_toHtml();
        }
        return '<li><a ' . $this->getLinkAttributes() . '>' . $this->escapeHtml($this->getLabel()) . '</a></li>';
    }
}
