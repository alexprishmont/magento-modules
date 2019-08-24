<?php

namespace Alexpr\IssuesHandler\Block\Adminhtml\System\Config\Form\Field;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\Data\Form\Element\Factory;

class StatusMap extends AbstractFieldArray
{
    protected $elementFactory;

    public function __construct(
        Context $context,
        Factory $elementFactory,
        array $data = []
    ) {
        $this->elementFactory = $elementFactory;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        $this->addColumn('status', ['label' => __('Status name')]);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
        parent::_prepareLayout();
    }
}
