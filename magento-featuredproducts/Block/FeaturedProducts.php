<?php
declare(strict_types=1);

namespace Alexpr\FeaturedProducts\Block;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Url\Helper\Data;

class FeaturedProducts extends ListProduct
{
    protected $helper;
    protected $productCollection;

    public function __construct(
        Context $context,
        PostHelper $postDataHelper,
        Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        Data $urlHelper,
        \Alexpr\FeaturedProducts\Helper\Data $configHelper,
        CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->helper = $configHelper;
        $this->productCollection = $collectionFactory->create();
        parent::__construct($context, $postDataHelper, $layerResolver, $categoryRepository, $urlHelper, $data);
    }

    public function getLoadedProductCollection()
    {
        return $this->productCollection
            ->addAttributeToFilter('is_featured', true)
            ->addFieldToSelect('*')
            ->setPageSize($this->helper->getCollectionLimit())
            ->setCurPage(1)
            ->load();
    }
}