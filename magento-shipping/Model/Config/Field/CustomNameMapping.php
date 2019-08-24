<?php

namespace Alexpr\SimpleShipping\Model\Config\Field;

use Magento\Config\Model\Config\Backend\Serialized\ArraySerialized;

class CustomNameMapping extends ArraySerialized
{

    public function beforeSave()
    {
        $value = $this->getValue();
        if (is_array($value)) {
            unset($value['__empty']);
        }

        $result = [];

        foreach ($value as $data) {
            if (!isset($data['free_shipping_applicable'])) {
                $data['free_shipping_applicable'] = 0;
            }
            $result[] = $data;
        }
        $this->setValue($result);
        return parent::beforeSave();
    }

}