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
 * @author    Liene Tunne <info@magebit.com>
 * @license   GNU General Public License ("GPL") v3.0
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Magebit\StaticContentProcessor\Model\Indexer\DataProvider\Category;

use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Divante\VsbridgeIndexerCore\Api\DataProviderInterface;
use Magento\Catalog\Model\Config;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class WysiwygBlockData
 */
class ImageUrlRewrite implements DataProviderInterface
{
    /**
     * Entity code
     */
    const ENTITY = 'catalog_category';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Config
     */
    private $catalogConfig;


    /**
     * ImageUrlRewrite constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param Config $catalogConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Config $catalogConfig
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->catalogConfig = $catalogConfig;
    }

    /**
     * @param array $indexData
     * @param int $storeId
     * @return array
     * @throws LocalizedException
     */
    public function addData(array $indexData, $storeId): array
    {
        $processable = [];

        $rewriteCatMediaUrl = $this->scopeConfig->getValue(
            'vsbridge_indexer_settings/url_rewrites/parse_category_media',
            ScopeInterface::SCOPE_STORE
        );

        if ($rewriteCatMediaUrl) {
            foreach ($indexData as $categoryId => $categoryData) {
                foreach ($categoryData as $attributeCode => $attribute) {

                   if ($this->checkAttributeIsImage($attributeCode)) {
                       $processable[] = [
                           'categoryId' => $categoryId,
                           'attributeId' => $attributeCode,
                           'content' => $attribute
                       ];
                   }
                }
            }

            $filteredIndexData = $this->convertMediaUrl($processable);

            foreach ($filteredIndexData as $data) {
                $indexData[$data['categoryId']][$data['attributeId']] = $data['content'];
            }
        }

        return $indexData;
    }

    /**
     * @param string|null $attributeCode
     * @return bool
     * @throws LocalizedException
     */
    protected function checkAttributeIsImage(?string $attributeCode): bool
    {
        $attributeData = $this->catalogConfig->getAttribute(self::ENTITY, $attributeCode);

        return $attributeData->getFrontendInput() == 'image' ? true : false;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function convertMediaUrl(array $data): array
    {
        $urlRewritesMedia = $this->scopeConfig->getValue(
            'vsbridge_indexer_settings/url_rewrites/media_url',
            ScopeInterface::SCOPE_STORE
        );

        foreach ($data as &$attribute) {
            // Remove store Url
            $cleanVSFMediaPath = substr($urlRewritesMedia, strpos($urlRewritesMedia, '/media'));
            $attribute['content']  = str_replace('/media', $cleanVSFMediaPath, $attribute['content']);
        }

        return $data;
    }
}
