<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the landofcoder.com license that is
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

namespace Lofmp\ChatSystem\Block\Adminhtml\Chat\Edit\Tab;

use Lofmp\ChatSystem\Model\Chat;
use Lofmp\ChatSystem\Model\ChatMessage;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

/**
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class Main extends Template
{
    /**
     * @var Registry|null
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
     * @var Session
     */
    protected $authSession;

    /**
     * @var ChatMessage
     */
    protected $messsage;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param Session $authSession
     * @param ChatMessage $messsage
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Session $authSession,
        ChatMessage $messsage
    ) {
        parent::__construct($context);
        $this->_coreRegistry = $registry;
        $this->formKey = $context->getFormKey();
        $this->authSession = $authSession;
        $this->messsage = $messsage;
    }

    public function getCurrentChat()
    {
        return $this->_coreRegistry->registry('lofmpchatsystem_chat');
    }

    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }

    public function getUser()
    {
        $user = $this->authSession->getUser();
        return $user;
    }

    /**
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function isRead()
    {
        $objectManager = ObjectManager::getInstance();
        $chat = $objectManager
            ->create(Chat::class)
            ->load($this->getCurrentChat()->getData('chat_id'));
        $messsage = $this->messsage->getCollection()
            ->addFieldToFilter('chat_id', $this->getCurrentChat()->getData('chat_id'))
            ->addFieldToFilter('is_read', 1);
        foreach ($messsage as $_messsage) {
            $_messsage->setData('is_read', 0)->save();
        }

        $chat->setData('is_read', 0)->save();
    }
}
