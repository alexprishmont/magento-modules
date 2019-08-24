<?php

namespace Alexpr\SimpleShipping\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class RoundRuleList implements ArrayInterface
{
    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'up', 'label' => __('Round Up')],
            ['value' => 'down', 'label' => __('Round Down')]
        ];
    }
}