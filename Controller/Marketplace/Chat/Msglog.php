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
namespace Lofmp\ChatSystem\Controller\Marketplace\Chat;

use Laminas\Mvc\Controller\Plugin\Service\ForwardFactory;
use Lofmp\ChatSystem\Helper\Data;
use Lofmp\ChatSystem\Model\Chat;
use Lofmp\ChatSystem\Model\ChatMessage;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

/**
 * Display Hello on screen
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD)
 */
class Msglog extends Action
{
    protected $_cacheTypeList;
    /**
     * @var RequestInterface
     */
    protected $_request;

    /**
     * @var ResponseInterface
     */
    protected $_response;

    /**
     * @var RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var ResultFactory
     */
    protected $resultFactory;

    /**
     * @var Data
     */
    protected $_helper;

    /**
     * @var ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var ChatMessage
     */
    protected $_message;

    /**
     * @var Chat
     */
    protected $_chat;

    /**
     * @var \Lof\MarketPlace\Helper\Data
     */
    protected $marketHelper;

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var Registry
     */
    private $_coreRegistry;

    /**
     * @var Session
     */
    private $_customerSession;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Data $helper
     * @param \Lof\MarketPlace\Helper\Data $marketHelper
     * @param ChatMessage $message
     * @param Chat $chat
     * @param ForwardFactory $resultForwardFactory
     * @param Registry $registry
     * @param TypeListInterface $cacheTypeList
     * @param Session $customerSession
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Data $helper,
        \Lof\MarketPlace\Helper\Data $marketHelper,
        ChatMessage $message,
        Chat $chat,
        ForwardFactory $resultForwardFactory,
        Registry $registry,
        TypeListInterface $cacheTypeList,
        Session $customerSession
    ) {
        $this->_chat = $chat;
        $this->resultPageFactory    = $resultPageFactory;
        $this->_helper              = $helper;
        $this->marketHelper         = $marketHelper;
        $this->_message             = $message;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->_coreRegistry        = $registry;
        $this->_cacheTypeList       = $cacheTypeList;
        $this->_customerSession     = $customerSession;
        $this->_request             = $context->getRequest();
        parent::__construct($context);
    }

    /**
     * Default customer account page
     *
     * @return void
     * phpcs:disable Magento2.Security.LanguageConstruct.DirectOutput
     * phpcs:disable Magento2.Security.LanguageConstruct.ExitUsage
     */
    public function execute()
    {
        if (!$this->_helper->isEnabled()) {
            return;
        }
        $id = $this->getRequest()->getparam('chat_id');
        $chat = $this->_chat->getCollection()->addFieldToFilter('chat_id', $id)
        ->addFieldToFilter('seller_id', $this->marketHelper->getSellerId());
        $chat_id = $chat->getFirstItem()->getData('chat_id');
        $message = $this->_message->getCollection()->addFieldToFilter('chat_id', $chat_id);

        foreach ($message as $key => $_message) {
            $_message['body_msg'] = $this->_helper->xss_clean($_message['body_msg']);
            $_message['seller_name'] = $this->_helper->xss_clean($_message['seller_name']);
            $_message['created_at'] = strtotime($_message['created_at']);
            if ($_message['seller_id']) {
                echo '
                    <div class="msg-user">
                        <p>'.$_message['body_msg'].'</p>
                        <div class="info-msg-user">
                            '.__("You").', '.date('d-m-Y g:i a', $_message['created_at']).'
                        </div>
                    </div>

                ';
            } elseif ($_message['user_id']) {
                echo '
                    <div class="msg-user">
                        <p>'.$_message['body_msg'].'</p>
                        <div class="info-msg-user">
                            '.$_message['user_name'].', '.date('d-m-Y g:i a', $_message['created_at']).'
                        </div>
                    </div>

                ';
            } else {
                echo '
                <div class="msg">
                    <p>'.$_message['body_msg'].'</p>
                    <div class="info-msg">';
                if ($_message['customer_name'] != " ") {
                    echo $_message['customer_name'].', '.date('d-m-Y g:i a', $_message['created_at']);
                } else {
                    echo __('Guest'.', '.date('d-m-Y g:i a', $_message['created_at']));
                }
                echo '</div>
                </div>
            ';
            }
        }
        exit;
    }
}
