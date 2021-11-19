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
 * @copyright  Copyright (c) 2017 Landofcoder (http://www.landofcoder.com/)
 * @license    http://www.landofcoder.com/LICENSE-1.0.html
 */


namespace Lofmp\ChatSystem\Controller\Marketplace\Chat;

use Lof\ChatSystem\Helper\Data;
use Lof\MarketPlace\Model\SalesFactory;
use Lof\MarketPlace\Model\SellerFactory;
use Lofmp\ChatSystem\Model\Chat;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ActionFlag;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Registry;
use Magento\Framework\Url;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 * @SuppressWarnings(PHPMD)
 */
class Edit extends Action
{
    /**
     * @var Magento\Framework\App\Action\Session
     */
    protected $session;

    /**
     *
     * @var Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
  
    /**
     * @var SalesFactory
     */
    protected $sellerFactory;

    const FLAG_IS_URLS_CHECKED = 'check_url_settings';

    protected $_frontendUrl;

    /**
     * @var ActionFlag
     */
    protected $_actionFlag;
  
    /**
     * @var Data
     */
    protected $_assignHelper;

    protected $_coreRegistry;

    /**
     *
     * @param Context $context
     * @param Session $customerSession
     * @param SellerFactory $sellerFactory
     * @param Url $frontendUrl
     * @param \Lofmp\ChatSystem\Helper\Data $helper
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        SellerFactory $sellerFactory,
        Url $frontendUrl,
        \Lofmp\ChatSystem\Helper\Data $helper,
        Registry $coreRegistry,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->_coreRegistry = $coreRegistry;
        $this->_assignHelper = $helper;
        $this->_frontendUrl = $frontendUrl;
        $this->_actionFlag = $context->getActionFlag();
        $this->sellerFactory     = $sellerFactory;
        $this->session           = $customerSession;
        $this->resultPageFactory = $resultPageFactory;
    }
    public function getFrontendUrl($route = '', $params = [])
    {
        return $this->_frontendUrl->getUrl($route, $params);
    }
    /**
     * Redirect to URL
     * @param string $url
     * @return ResponseInterface
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _redirectUrl($url)
    {
        $this->getResponse()->setRedirect($url);
        $this->session->setIsUrlNotice($this->_actionFlag->get('', self::FLAG_IS_URLS_CHECKED));
        return $this->getResponse();
    }

    /**
     * Customer login form page
     *
     * @return Redirect|Page
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function execute()
    {
        if (!$this->_assignHelper->isEnabled()) {
            $this->messageManager->addError(__("The feature is not available at now."));
            return $this->_redirect('catalog/dashboard');
        }
        $customerSession = $this->session;
        $customerId = $customerSession->getId();
        $status = $this->sellerFactory->create()->load($customerId, 'customer_id')->getStatus();

        if ($customerSession->isLoggedIn() && $status == 1) {
            $id = $this->getRequest()->getParam('chat_id');
            $chat = $this->_objectManager->create(Chat::class);
            $chat->load($id);

            if ($id && (!$chat->getId())
            ) {
                $this->messageManager->addError(__("This chat does not exist."));
                return $this->_redirect('*/*');
            }

            $this->_coreRegistry->register('lofmpchatsystem_chat', $chat);
            $this->_coreRegistry->register('chat', $chat);
            $title = $this->_view->getPage()->getConfig()->getTitle();
            $title->prepend(__("Catalog"));
            $title->prepend(__("Action Chat"));
            $resultPage = $this->resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->set(__('ChatSystem Chat'));
            return $resultPage;
        } elseif ($customerSession->isLoggedIn() && $status == 0) {
            $this->_redirectUrl($this->getFrontendUrl('lofmarketplace/seller/becomeseller'));
        } else {
            $this->messageManager->addNotice(__('You must have a seller account to access'));
            $this->_redirectUrl($this->getFrontendUrl('lofmarketplace/seller/login'));
        }
    }
}
