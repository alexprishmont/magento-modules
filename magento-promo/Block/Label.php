<?php
declare(strict_types=1);

namespace Alexpr\PromoLabel\Block;

use Magento\Catalog\Helper\Data;
use Magento\Framework\View\Element\Template;

class Label extends Template
{
    protected $helper;
    protected $product;
    protected $config;

    public function __construct(
        Template\Context $context,
        Data $helper,
        \Alexpr\PromoLabel\Helper\Data $config,
        array $data = []
    ) {
        $this->helper = $helper;
        $this->config = $config;
        parent::__construct($context, $data);
    }

    public function isProductPromotional()
    {
        if ($this->getTimeleft() !== false && $this->config->getGeneralConfig('enable')) {
            if (is_null($this->product)) {
                $this->product = $this->helper->getProduct();
            }
            $promo = $this->product->getData('is_promo');
            return $promo;
        }
        return false;
    }

    public function getTimeleft()
    {
        $endDate = new \DateTime($this->config->getGeneralConfig('end_date'));
        $today = new \DateTime('now');
        $difference = $today->diff($endDate);
        if ($difference) {
            return $this->getTimeleftString($difference);
        }
        return false;
    }

    public function getLabelBg()
    {
        return $this->config->getGeneralConfig('background');
    }

    public function getTextColor()
    {
        return $this->config->getGeneralConfig('textcolor');
    }

    public function getLabelText()
    {
        return trim($this->config->getGeneralConfig('labeltext'));
    }

    private function getTimeleftString($difference)
    {
        $days = $difference->format('%d');
        $hours = $difference->format('%h');
        $mins = $difference->format('%i');
        $secs = $difference->format('%s');

        $timeleft = '';

        if ($days) {
            $timeleft .= $days . ' days ';
        }

        if ($hours) {
            $timeleft .= $hours . ' hours ';
        }

        if ($mins) {
            $timeleft .= $mins . ' minutes ';
        }

        if ($secs) {
            $timeleft .= $secs . ' seconds ';
        }

        return $timeleft . 'left until promo end.';
    }
}
