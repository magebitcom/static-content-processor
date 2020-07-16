<?php declare(strict_types = 1);
/**
 * This file is part of the Magebit_StaticContentProcessor package.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magebit_StaticContentProcessor
 * to newer versions in the future.
 *
 * @copyright Copyright (c) 2020 Magebit, Ltd. (https://magebit.com/)
 * @author    Kristofers Ozolins <kristofers.ozolins@magebit.com>
 * @license   GNU General Public License ("GPL") v3.0
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Magebit\StaticContentProcessor\Plugin\Model;

use InvalidArgumentException;
use Magebit\StaticContentProcessor\Helper\Resolver;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;

/**
 * @package Magebit\StaticContentProcessor\Plugin\Model
 */
class ContentProcessor
{
    /**
     * @var Resolver
     */
    protected $resolver;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param Resolver $resolver
     * @return void
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Resolver $resolver
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->resolver = $resolver;
    }

    /**
     * Converts magento urls during indexation
     * @param \Divante\VsbridgeIndexerCms\Model\ContentProcessor $subject
     * @param mixed $result
     * @return string
     * @throws NoSuchEntityException
     * @throws InvalidArgumentException
     */
    public function afterParse(\Divante\VsbridgeIndexerCms\Model\ContentProcessor $subject, $result)
    {
        $enabled = $this->scopeConfig->getValue(
            'vsbridge_indexer_settings/url_rewrites/enabled',
            ScopeInterface::SCOPE_STORE
        );

        if ($enabled) {
            return $this->resolver->resolve($result);
        }

        return $result;
    }
}
