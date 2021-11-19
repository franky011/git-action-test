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
 * @copyright  Copyright (c) 2016 Landofcoder (http://www.landofcoder.com/)
 * @license    http://www.landofcoder.com/LICENSE-1.0.html
 */

namespace Lofmp\ChatSystem\Block\Adminhtml\Chat;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form\Container;
use Magento\Framework\Phrase;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\AbstractBlock;

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
     * Initialize cms page edit block
     *
     * @return void
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct()
    {
        $this->_objectId = 'chat_id';
        $this->_blockGroup = 'Lofmp_ChatSystem';
        $this->_controller = 'adminhtml_chat';

        parent::_construct();
    }

    /**
     * Retrieve text for header element depending on loaded page
     *
     * @return Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('lofmpchatsystem_chat')->getId()) {
            return __(
                "Edit Chat '%1'",
                $this->escapeHtml($this->_coreRegistry->registry('lofmpchatsystem_chat')->getTitle())
            );
        } else {
            return __('New Chat');
        }
    }

    /**
     * Check chat for passed action
     *
     * @param string $resourceId
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Prepare layout
     *
     * @return AbstractBlock
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _prepareLayout()
    {
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('page_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'page_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'page_content');
                }
            };
        ";
        return parent::_prepareLayout();
    }
}
