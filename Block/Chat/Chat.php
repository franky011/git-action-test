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

namespace Lofmp\ChatSystem\Block\Chat;

use Lof\MarketPlace\Helper\Data;
use Lof\MarketPlace\Helper\Seller;
use Lofmp\ChatSystem\Helper\Url;
use Magento\Customer\Model\Context;
use Magento\Customer\Model\Form;
use Magento\Customer\Model\Session;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Chat extends Template
{
    /**
     * @var int
     */
    private $_username = -1;
    /**
     *
     * @var Magento\Framework\App\Action\Session
     */
    protected $_customerSession;
    /**
     *
     * @var \Magento\Customer\Model\Url
     */
    protected $chat;

    /**
     *
     * @var \Lofmp\ChatSystem\Helper\Data
     */
    protected $helper;
    /**
     *
     * @var \Magento\Customer\Model\Url
     */
    protected $_customerUrl;

    /**
     * @var Data
     */
    protected $marketHelper;

    /**
     * @var Seller
     */
    protected $sellerHelper;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    /**
     * @var Registry
     */
    private $_coreRegistry;

    /**
     * Chat constructor.
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param Session $customerSession
     * @param Data $marketHelper
     * @param Seller $sellerHelper
     * @param Url $customerUrl
     * @param \Lofmp\ChatSystem\Helper\Data $helper
     * @param \Lofmp\ChatSystem\Model\Chat $chat
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        Session $customerSession,
        Data $marketHelper,
        Seller $sellerHelper,
        Url $customerUrl,
        \Lofmp\ChatSystem\Helper\Data $helper,
        \Lofmp\ChatSystem\Model\Chat $chat,
        \Magento\Framework\App\Http\Context $httpContext,
        array $data = []
    ) {
        $this->_coreRegistry = $context->getRegistry();
        $this->marketHelper = $marketHelper;
        $this->sellerHelper = $sellerHelper;
        $this->helper = $helper;
        $this->chat = $chat;
        $this->_customerSession = $customerSession;
        $this->_customerUrl = $customerUrl;
        $this->httpContext = $httpContext;
        parent::__construct($context, $data);
    }

    public function getCurrentSeller()
    {
        $seller = $this->_coreRegistry->registry('current_seller');
        if ($seller) {
            $this->setData('current_seller', $seller);
        }
        return $seller;
    }

    public function getProduct()
    {
        $curPro = $this->_coreRegistry->registry('current_product');
        return $curPro;
    }

    public function isLogin()
    {
        return (bool)$this->httpContext->getValue(Context::CONTEXT_AUTH);
    }

    public function getCurrentUrl()
    {
        return $this->_urlBuilder->getCurrentUrl();
    }

    public function getCustomer()
    {
        return $this->httpContext;
    }

    /**
     * Retrieve form posting url
     *
     * @return string
     */
    public function getPostActionUrl()
    {
        return $this->_customerUrl->getLoginPostUrl();
    }

    /**
     * Retrieve password forgotten url
     *
     * @return string
     */
    public function getForgotPasswordUrl()
    {
        return $this->_customerUrl->getForgotPasswordUrl();
    }

    public function getRegisterUrl()
    {
        return $this->_customerUrl->getRegisterUrl();
    }

    /**
     * Retrieve username for form field
     *
     * @return string
     */
    public function getUsername()
    {
        if (-1 === $this->_username) {
            $this->_username = $this->_customerSession->getUsername(true);
        }
        return $this->_username;
    }

    /**
     * Check if autocomplete is disabled on storefront
     *
     * @return bool
     */
    public function isAutocompleteDisabled()
    {
        return ( bool )!$this->_scopeConfig->getValue(
            Form::XML_PATH_ENABLE_AUTOCOMPLETE,
            ScopeInterface::SCOPE_STORE
        );
    }
}
