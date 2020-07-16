<?php
/**
 * Magebit_StaticContentProcessor
 *
 * @category  Magebit
 * @package   Magebit_StaticContentProcessor
 * @author    Kristofers Ozoliņš <kristofers.ozolins@magebit.com>
 * @copyright 2020 [Magebit, Ltd.(http://www.magebit.com/)]
 */

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'Magebit_StaticContentProcessor',
    __DIR__
);
