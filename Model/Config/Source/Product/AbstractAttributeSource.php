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
use Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;
use Magento\Framework\Option\ArrayInterface;

/**
 * Class AbstractAttributeSource
 */
abstract class AbstractAttributeSource implements ArrayInterface
{
    /**
     * @var array|null
     */
    private $options;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * Attributes constructor.
     *
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (null === $this->options) {
            $this->options = [];
            /** @var Collection $collection */
            $collection = $this->collectionFactory->create();
            $collection->addVisibleFilter();
            $attributes = $collection->getItems();

            /** @var ProductAttributeInterface $attribute */
            foreach ($attributes as $attribute) {
                if ($this->canAddAttribute($attribute)) {
                    $label = sprintf(
                        '%s (%s)',
                        $attribute->getDefaultFrontendLabel(),
                        $attribute->getAttributeCode()
                    );

                    $this->options[] = [
                        'label' => $label,
                        'value' => $attribute->getAttributeCode(),
                    ];
                }
            }
        }

        return $this->options;
    }

    /**
     * Validate if attribute can be shown
     *
     * @param ProductAttributeInterface $attribute
     *
     * @return bool
     */
    abstract public function canAddAttribute(ProductAttributeInterface $attribute): bool;
}
