<?php

namespace Alexpr\SimpleShipping\Block\Adminhtml\System\Config\Form\Field;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\Data\Form\Element\Factory;

class CustomNameMapping extends AbstractFieldArray
{
    protected $elementFactory;
    private $checkbox;

    public function __construct(
        Context $context,
        Factory $elementFactory,
        Checkbox $checkbox,
        array $data = []
    ) {
        $this->elementFactory = $elementFactory;
        $this->checkbox = $checkbox;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        $this->addColumn('default_name', ['label' => __('Default carrier name')]);
        $this->addColumn('custom_name', ['label' => __('New carrier name')]);
        $this->addColumn(
            'free_shipping_applicable',
            [
                'label' => __('Free Shipping Applicable'),
                'renderer' => $this->checkbox
            ]
        );
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
        parent::_prepareLayout();
    }
}
