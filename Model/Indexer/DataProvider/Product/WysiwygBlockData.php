<?php declare(strict_types=1);
/**
 * This file is part of the Magebit_StaticContentProcessor package
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magebit_StaticContentProcessor
 * to newer versions in the future.
 *
 * @copyright Copyright (c) 2021 Magebit (https://magebit.com/)
 * @author    Kristofers Ozolins <info@magebit.com>
 * @license   GNU General Public License ("GPL") v3.0
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Magebit\StaticContentProcessor\Model\Indexer\DataProvider\Product;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Divante\VsbridgeIndexerCore\Api\DataProviderInterface;
use Divante\VsbridgeIndexerCms\Model\Indexer\DataProvider\CmsContentFilter;
use Divante\VsbridgeIndexerCatalog\Model\Category\GetAttributeCodesByIds;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class WysiwygBlockData
 */
class WysiwygBlockData implements DataProviderInterface
{
    /**
     * @var CmsContentFilter
     */
    private $cmsContentFilter;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * ContentData constructor.
     *
     * @param CmsContentFilter $cmsContentFilter
     */
    public function __construct(
        CmsContentFilter $cmsContentFilter,
        ScopeConfigInterface $scopeConfig,
        GetAttributeCodesByIds $getAttributeCodesByIds
    ) {
        $this->cmsContentFilter = $cmsContentFilter;
        $this->scopeConfig = $scopeConfig;
        $this->getAttributeCodesByIds = $getAttributeCodesByIds;
    }

    /**
     * @inheritdoc
     */
    public function addData(array $indexData, $storeId)
    {
        $cmsContentAttributes = $this->scopeConfig->getValue(
            'vsbridge_indexer_settings/url_rewrites/product_content_attributes',
            ScopeInterface::SCOPE_STORE
        );

        if ($cmsContentAttributes) {
            $processable = [];
            $attributes = explode(',', $cmsContentAttributes);

            $propertyAccessor = PropertyAccess::createPropertyAccessor();

            foreach ($indexData as $productId => $productData) {
                $this->populateProcessable($processable, $attributes, $productData, "[{$productId}]");

                if (isset($productData['configurable_children'])) {
                    foreach ($productData['configurable_children'] as $key => $child) {
                        $this->populateProcessable($processable, $attributes, $child, "[{$productId}][configurable_children][{$key}]");
                    }
                }
            }

            $filteredIndexData = $this->cmsContentFilter->filter($processable, $storeId, 'block');

            foreach ($filteredIndexData as $data) {
                $propertyAccessor->setValue($indexData, $data['path'], $data['content']);
            }
        }

        return $indexData;
    }

    /**
     * @param mixed $processable
     * @param mixed $attributes
     * @param mixed $arr
     * @param string $basePath
     * @return void
     */
    protected function populateProcessable(&$processable, $attributes, $arr, $basePath = '')
    {
        foreach ($arr as $attributeCode => $attribute) {
            if (in_array($attributeCode, $attributes) && is_string($attribute)) {
                $processable[] = [
                    'path' => "{$basePath}[{$attributeCode}]",
                    'content' => $attribute
                ];
            }
        }
    }
}
