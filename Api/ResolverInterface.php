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
