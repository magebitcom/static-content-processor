<?php declare(strict_types = 1);
/**
 * Magebit_StaticContentProcessor
 *
 * @category  Magebit
 * @package   Magebit_StaticContentProcessor
 * @author    Kristofers Ozoliņš <kristofers.ozolins@magebit.com>
 * @copyright 2020 [Magebit, Ltd.(http://www.magebit.com/)]
 */

namespace Magebit\StaticContentProcessor\Helper;

use InvalidArgumentException;
use Magebit\StaticContentProcessor\Api\ResolverInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * @package Magebit\StaticContentProcessor\Helper
 */
class Resolver extends AbstractHelper
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Resolver constructor.
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     * @param Context $context
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        Context $context
    ) {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Converts static magento URL's to VSF url's
     *
     * @param string $string
     * @param null|int $storeId
     * @param array $rewrites
     * @return string
     * @throws NoSuchEntityException
     * @throws InvalidArgumentException
     */
    public function resolve(string $string, ?int $storeId = null, $rewrites = [ ResolverInterface::REWRITE_ALL ])
    {
        /** @var \Magento\Store\Model\Store $currentStore */
        $currentStore = $this->storeManager->getStore($storeId);

        $match = [];
        $replace = [];

        $urlRewritesVsf = $this->scopeConfig->getValue(
            'vsbridge_indexer_settings/url_rewrites/url',
            ScopeInterface::SCOPE_STORE
        );

        $urlRewritesMedia = $this->scopeConfig->getValue(
            'vsbridge_indexer_settings/url_rewrites/media_url',
            ScopeInterface::SCOPE_STORE
        );

        foreach ($rewrites as $rewrite) {
            switch ($rewrite) {
                case ResolverInterface::REWRITE_DIRECT_LINK:
                    $match[] = $currentStore->getBaseUrl(UrlInterface::URL_TYPE_DIRECT_LINK);
                    $replace[] = $this->trailingPath($urlRewritesVsf);
                break;
                case ResolverInterface::REWRITE_LINK:
                    $match[] = $currentStore->getBaseUrl(UrlInterface::URL_TYPE_LINK);
                    $replace[] = $this->trailingPath($urlRewritesVsf);
                break;
                case ResolverInterface::REWRITE_MEDIA:
                    $match[] = $currentStore->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
                    $replace[] = $this->trailingPath($urlRewritesMedia);
                break;
                case ResolverInterface::REWRITE_STORELINK:
                    $match[] = "%7B%7Bstorelink%7D%7D";
                    $replace[] = $currentStore->getBaseUrl(UrlInterface::URL_TYPE_WEB);
                break;
                case ResolverInterface::REWRITE_WEB:
                    $match[] = $currentStore->getBaseUrl(UrlInterface::URL_TYPE_WEB);
                    $replace[] = $this->trailingPath($urlRewritesVsf);
                break;
                case ResolverInterface::REWRITE_ALL:
                    $match = [
                        $currentStore->getBaseUrl(UrlInterface::URL_TYPE_MEDIA),
                        $currentStore->getBaseUrl(UrlInterface::URL_TYPE_LINK),
                        $currentStore->getBaseUrl(UrlInterface::URL_TYPE_DIRECT_LINK),
                        $currentStore->getBaseUrl(UrlInterface::URL_TYPE_WEB),
                        "%7B%7Bstorelink%7D%7D",
                    ];

                    $replace = [
                        $this->trailingPath($urlRewritesMedia),
                        $this->trailingPath($urlRewritesVsf),
                        $this->trailingPath($urlRewritesVsf),
                        $this->trailingPath($urlRewritesVsf),
                        $currentStore->getBaseUrl(UrlInterface::URL_TYPE_WEB),
                    ];
                break;
            }
        }

        if (is_string($string)) {
            $string = str_replace(
                $match,
                $replace,
                $string
            );
        }

        return $string;
    }

    /**
     * @param mixed $path
     * @return string
     */
    protected function trailingPath($path)
    {
        if (!$path) {
            return '';
        }

        return rtrim($path, '/') . '/';
    }
}
