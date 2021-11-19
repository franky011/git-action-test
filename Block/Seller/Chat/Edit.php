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
 * @package    Lofmp_ChatSystem
 * @copyright  Copyright (c) 2017 Landofcoder (http://www.landofcoder.com/)
 * @license    http://www.landofcoder.com/LICENSE-1.0.html
 */

namespace Lofmp\ChatSystem\Block\Seller\Chat;

use Lofmp\ChatSystem\Block\Seller\Widget\Form\Container;
use Lofmp\ChatSystem\Model\Chat;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Phrase;
use Magento\Framework\Registry;

/**
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class Edit extends Container
{
    /**
     * Core registry
     *
     * @var Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Internal constructor
     *
     * @return void
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'Lofmp_ChatSystem';
        $this->_controller = 'seller_chat';
        parent::_construct();
    }

    /**
     * Prepare layout.
     * Adding save_and_continue button
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * @return $this
     */
    protected function _preparelayout()
    {
        return parent::_prepareLayout();
    }

    /**
     * Return translated header text depending on creating/editing action
     *
     * @return Phrase
     */
    public function getHeaderText()
    {
        return __('Chat');
    }
}
