<?php declare(strict_types = 1);
/**
 * Magebit_StaticContentProcessor
 *
 * @category  Magebit
 * @package   Magebit_StaticContentProcessor
 * @author    Kristofers Ozoliņš <kristofers.ozolins@magebit.com>
 * @copyright 2020 [Magebit, Ltd.(http://www.magebit.com/)]
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
