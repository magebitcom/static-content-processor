<?php declare(strict_types = 1);
/**
 * Magebit_StaticContentProcessor
 *
 * @category  Magebit
 * @package   Magebit_StaticContentProcessor
 * @author    Kristofers Ozoliņš <kristofers.ozolins@magebit.com>
 * @copyright 2020 [Magebit, Ltd.(http://www.magebit.com/)]
 */

namespace Magebit\StaticContentProcessor\Api;

/**
 * @package Magebit\StaticContentProcessor\Api
 */
interface ResolverInterface
{
    const REWRITE_ALL = 'all';
    const REWRITE_MEDIA = 'media';
    const REWRITE_LINK = 'link';
    const REWRITE_DIRECT_LINK = 'direct_link';
    const REWRITE_WEB = 'web';
    const REWRITE_STORELINK = 'storelink';
}
