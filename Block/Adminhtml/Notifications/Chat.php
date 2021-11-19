<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * http://landofcoder.com/license
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lofmp_ChatSystem
 *
 * @copyright  Copyright (c) 2016 Landofcoder (http://www.landofcoder.com/)
 * @license    http://www.landofcoder.com/LICENSE-1.0.html
 */

namespace Lofmp\ChatSystem\Block\Adminhtml\Notifications;

use Lofmp\ChatSystem\Model\ChatMessage;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\View\Element\Template;

class Chat extends Template
{
    /**
     * @var ChatMessage
     */
    protected $message;

    /**
     * Chat constructor.
     * @param Context $context
     * @param ChatMessage $message
     */
    public function __construct(
        Context $context,
        ChatMessage $message
    ) {
        $this->message = $message;
        parent::__construct($context);
    }
}
