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


namespace Lofmp\ChatSystem\Controller\Marketplace\Chat ;

use Lof\MarketPlace\Model\SalesFactory;
use Lof\MarketPlace\Model\SellerFactory;
use Lofmp\ChatSystem\Helper\Data;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ActionFlag;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Url;
use Magento\Framework\View\Result\PageFactory;

/**
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class Index extends Action
{
    /**
     *
     * @var Magento\Framework\App\Action\Session
     */
    protected $session;

    /**
     *
     * @var Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    /**
     *
     * @var SalesFactory
     */
    protected $sellerFactory;

    const FLAG_IS_URLS_CHECKED = 'check_url_settings';

    /**
     * @var Url
     */
    protected $_frontendUrl;

    /**
     * @var ActionFlag
     */
    protected $_actionFlag;

    /**
     * @var Data
     */
    protected $helper;

    /**
     *
     * @param Context $context
     * @param Session $customerSession
     * @param SellerFactory $sellerFactory
     * @param Url $frontendUrl
     * @param PageFactory $resultPageFactory
     * @param Data $helper
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        SellerFactory $sellerFactory,
        Url $frontendUrl,
        PageFactory $resultPageFactory,
        Data $helper
    ) {
        parent::__construct($context);

        $this->_frontendUrl = $frontendUrl;
        $this->_actionFlag = $context->getActionFlag();
        $this->sellerFactory = $sellerFactory;
        $this->session = $customerSession;
        $this->resultPageFactory = $resultPageFactory;
        $this->helper = $helper;
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
     * @return ResponseInterface
     */
    public function execute()
    {
        if (!$this->helper->isEnabled()) {
            $this->messageManager->addError(__("The feature is not available at now."));
            return $this->_redirect('catalog/dashboard');
        }
        $customerSession = $this->session;
        $customerId = $customerSession->getId();
        $status = $this->sellerFactory->create()->load($customerId, 'customer_id')->getStatus();

        if ($customerSession->isLoggedIn() && $status == 1) {
            $this->_view->loadLayout();
            $this->_view->renderLayout();
        } elseif ($customerSession->isLoggedIn() && $status == 0) {
            $this->_redirectUrl($this->getFrontendUrl('lofmarketplace/seller/becomeseller'));
        } else {
            $this->messageManager->addNotice(__('You must have a seller account to access'));
            $this->_redirectUrl($this->getFrontendUrl('lofmarketplace/seller/login'));
        }
    }
}
