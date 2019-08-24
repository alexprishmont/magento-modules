<?php

namespace Alexpr\PromoLabel\Plugin;

class Product
{
    public function afterGetPrice(\Magento\Catalog\Model\Product $subject, $price)
    {
        if (!$subject->getData('is_promo')) {
            return $price;
        }

        return floatval($subject->getData('promo_price'));
    }
}