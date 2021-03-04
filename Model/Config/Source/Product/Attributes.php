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
namespace Magebit\StaticContentProcessor\Model\Config\Source\Product;

use Magento\Catalog\Api\Data\ProductAttributeInterface;

/**
 * Class Attributes
 */
class Attributes extends AbstractAttributeSource
{
    /**
     *
     */
    const GENERAL_RESTRICTED_ATTRIBUTES = [
        'sku',
        'url_path',
        'url_key',
        'name',
        'visibility',
        'status',
        'tier_price',
        'price',
        'price_type',
        'gallery',
        'status',
        'category_ids',
        'swatch_image',
        'quantity_and_stock_status',
        'options_container',
    ];

    /**
     * @inheritDoc
     *
     * @param ProductAttributeInterface $attribute
     *
     * @return bool
     */
    public function canAddAttribute(ProductAttributeInterface $attribute): bool
    {
        return !in_array($attribute->getAttributeCode(), self::GENERAL_RESTRICTED_ATTRIBUTES);
    }
}
