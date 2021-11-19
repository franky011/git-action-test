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

namespace Lofmp\ChatSystem\Block\Adminhtml\Chat\Edit;

use Lofmp\ChatSystem\Block\Adminhtml\Chat\Edit\Tab\Main;
use Magento\Framework\Exception\LocalizedException;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('ticket_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Chat Information'));
    }

    /**
     * @return void
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _prepareLayout()
    {
        $this->addTab(
            'main_section',
            [
                'label' => __('General'),
                'content' => $this->getLayout()
                    ->createBlock(Main::class)->toHtml()
            ]
        );
    }
}
