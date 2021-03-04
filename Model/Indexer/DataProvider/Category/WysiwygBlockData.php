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

namespace Magebit\StaticContentProcessor\Model\Indexer\DataProvider\Category;

use Divante\VsbridgeIndexerCatalog\Model\Category\GetAttributeCodesByIds;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Divante\VsbridgeIndexerCms\Model\Indexer\DataProvider\CmsContentFilter;
use Divante\VsbridgeIndexerCore\Api\DataProviderInterface;

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
        $processable = [];

        $cmsContentAttributes = $this->scopeConfig->getValue(
            'vsbridge_indexer_settings/url_rewrites/category_content_attributes',
            ScopeInterface::SCOPE_STORE
        );

        if (is_string($cmsContentAttributes)) {
            $parseBlockIds = explode(',', $cmsContentAttributes);

            foreach ($indexData as $categoryId => $categoryData) {
                foreach ($categoryData as $attributeCode => $attribute) {
                    if (in_array($attributeCode, $parseBlockIds) && is_string($attribute)) {
                        $processable[] = [
                            'categoryId' => $categoryId,
                            'attributeId' => $attributeCode,
                            'content' => $attribute
                        ];
                    }
                }
            }

            $filteredIndexData = $this->cmsContentFilter->filter($processable, $storeId, 'block');

            foreach ($filteredIndexData as $data) {
                $indexData[$data['categoryId']][$data['attributeId']] = $data['content'];
            }
        }

        return $indexData;
    }
}
