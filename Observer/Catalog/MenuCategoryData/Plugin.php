<?php

namespace Mestrona\CategoryRedirect\Observer\Catalog\MenuCategoryData;

class Plugin
{
    /**
    * @var \Mestrona\CategoryRedirect\Helper\Data
    */
    protected $_helper;

    /**
     * @param $helper
     */
    public function __construct(
        \Mestrona\CategoryRedirect\Helper\Data $helper
    ) {
        $this->_helper = $helper;
    }

    /**
     * Overwrite category active state if redirect is configured
     *
     * @see \Magento\Catalog\Observer\MenuCategoryData::getMenuCategoryData
     *
     * @param \Magento\Catalog\Helper\Category $subject
     * @param \Closure $proceed
     * @param $category
     * @return string
     */
    public function aroundGetMenuCategoryData(\Magento\Catalog\Observer\MenuCategoryData $subject, \Closure $proceed, $category) {
        $result = $proceed($category);
        if ($redirectUrl = $category->getRedirectUrl()) {
            $result['is_active'] = $this->_helper->isCurrent($redirectUrl);
            // $result['has_active'] = true; // FIXME: also determine has active
        }
        return $result;
    }

}