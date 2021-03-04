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
namespace Magebit\StaticContentProcessor\Model\Config\Source\Category;

use Magento\Eav\Model\Entity\Attribute;

/**
 * Class Attributes
 */
class Attributes extends AbstractAttributeSource
{
    /**
     *
     */
    const RESTRICTED_ATTRIBUTES = [
        'all_children',
        'children',
        'children_count',
        'url_path',
        'url_key',
        'name',
        'is_active',
        'level',
        'path_in_store',
        'path',
        'position',
    ];

    /**
     * @inheritDoc
     *
     * @param Attribute $attribute
     *
     * @return bool
     */
    public function canAddAttribute(Attribute $attribute): bool
    {
        return !in_array($attribute->getAttributeCode(), self::RESTRICTED_ATTRIBUTES);
    }
}
