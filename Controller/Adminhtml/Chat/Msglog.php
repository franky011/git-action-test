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
namespace Lofmp\ChatSystem\Controller\Adminhtml\Chat;

use Lof\MarketPlace\Model\Seller;
use Lofmp\ChatSystem\Helper\Data;
use Lofmp\ChatSystem\Model\Chat;
use Lofmp\ChatSystem\Model\ChatMessage;
use Magento\Customer\Controller\AccountInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManager;

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
    protected $chat;

    /**
     * @var Registry
     */
    private $_coreRegistry;

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var Session
     */
    private $_customerSession;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Data $helper
     * @param ChatMessage $message
     * @param Chat $chat
     * @param ForwardFactory $resultForwardFactory
     * @param Registry $registry
     * @param TypeListInterface $cacheTypeList
     * @param Session $customerSession
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Data $helper,
        ChatMessage $message,
        Chat $chat,
        ForwardFactory $resultForwardFactory,
        Registry $registry,
        TypeListInterface $cacheTypeList,
        Session $customerSession
    ) {
        $this->chat = $chat;
        $this->resultPageFactory    = $resultPageFactory;
        $this->_helper              = $helper;
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
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     * @SuppressWarnings(PHPMD.ShortVariable)
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     * phpcs:disable Magento2.Security.LanguageConstruct.DirectOutput
     */
    public function execute()
    {
        $id = $this->getRequest()->getparam('chat_id');

        if ($this->_customerSession->getCustomer()->getEmail()) {
            $message = $this->_message->getCollection()
                ->addFieldToFilter('customer_email', $this->_customerSession->getCustomer()->getEmail());
        } else {
            $message = $this->_message->getCollection()->addFieldToFilter('chat_id', $id);
        }

        foreach ($message as $key => $_message) {
            $_message['created_at'] = strtotime($_message['created_at']);
            if ($_message['user_id']) {
                echo '
                    <div class="msg-user">
                        <p>'.$_message['body_msg'].'</p>
                        <div class="info-msg-user">
                            You'.', '.date('d-m-Y g:i a', $_message['created_at']).'
                        </div>
                    </div>
                ';
            } elseif ($_message['seller_id']) {
                $objectManager = ObjectManager::getInstance();
                $seller = $objectManager->create(Seller::class)
                    ->load($_message['seller_id'], 'seller_id');
                echo '
                    <div class="msg">
                        <p>'.$_message['body_msg'].'</p>
                        <div class="info-msg">
                            '.$seller->getData('name').', '.date('d-m-Y g:i a', $_message['created_at']).'
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
    }
}
