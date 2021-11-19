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

namespace Lofmp\ChatSystem\Block\Seller\Chat\Edit;

use Lof\MarketPlace\Helper\Data;
use Lof\MarketPlace\Model\Seller;
use Lofmp\ChatSystem\Model\Chat;
use Lofmp\ChatSystem\Model\ChatMessage;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

/**
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class Form extends Template
{
    /**
     * Core registry
     *
     * @var Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var string
     */
    protected $_template = 'Lofmp_ChatSystem::chat/chat.phtml';

    /**
     * @var FormKey
     */
    protected $formKey;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var ChatMessage
     */
    protected $messsage;

    /**
     * Form constructor.
     * @param Context $context
     * @param Registry $registry
     * @param ChatMessage $messsage
     * @param Data $helper
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ChatMessage $messsage,
        Data $helper
    ) {
        parent::__construct($context);
        $this->helper = $helper;
        $this->_coreRegistry = $registry;
        $this->formKey = $context->getFormKey();
        $this->messsage = $messsage;
    }

    /**
     * @return mixed|null
     */
    public function getCurrentChat()
    {
        return $this->_coreRegistry->registry('lofmpchatsystem_chat');
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }

    /**
     * @return mixed
     */
    public function getSeller()
    {
        $objectManager = ObjectManager::getInstance();
        $seller = $objectManager->create(Seller::class)
            ->load($this->helper->getSellerId(), 'seller_id');
        return $seller;
    }

    /**
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function isRead()
    {
        $objectManager = ObjectManager::getInstance();
        $chat = $objectManager->create(Chat::class)
            ->load($this->getCurrentChat()->getData('chat_id'));
        $messsage = $this->messsage->getCollection()
            ->addFieldToFilter('chat_id', $this->getCurrentChat()->getData('chat_id'))
            ->addFieldToFilter('is_read', 1);
        foreach ($messsage as $key => $_messsage) {
            $_messsage->setData('is_read', 0)->save();
        }

        $chat->setData('is_read', 0)->save();
    }
}
