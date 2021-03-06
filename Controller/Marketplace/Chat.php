<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * http://www.landofcoder.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_ChatSystem
 * @copyright  Copyright (c) 2017 Landofcoder (http://www.landofcoder.com/)
 * @license    http://www.landofcoder.com/LICENSE-1.0.html
 */

namespace Lofmp\ChatSystem\Controller\MarketPlace;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

/**
 * BLog post controller
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
abstract class Chat extends Action
{
    /**
     * @param Context $context
     * phpcs:disable Generic.CodeAnalysis.UselessOverridingMethod.Found
     */
    public function __construct(
        Context $context
    ) {
        parent::__construct($context);
    }
}
