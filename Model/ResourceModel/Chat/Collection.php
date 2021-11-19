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
namespace Lofmp\ChatSystem\Model\ResourceModel\Chat;

use \Lofmp\ChatSystem\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @SuppressWarinings(PHPMD.CamelCasePropertyName)
     */
    protected $_idFieldName = 'chat_id';

    /**
     * @SuppressWarinings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct()
    {
        $this->_init('Lofmp\ChatSystem\Model\Chat', 'Lofmp\ChatSystem\Model\ResourceModel\Chat');
    }

    /**
     * Returns pairs category_id - title
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->_toOptionArray('chat_id', 'title');
    }

    /**
     * Add link attribute to filter.
     *
     * @param $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        $this->performAddStoreFilter($store, $withAdmin);

        return $this;
    }
}
