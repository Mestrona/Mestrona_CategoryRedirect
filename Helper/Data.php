<?php

namespace Mestrona\CategoryRedirect\Helper;

class Data
{
    /**
     * Url Builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlBuilder;

    /**
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     */
    public function __construct(
        \Magento\Framework\UrlInterface $urlBuilder
    ) {
        $this->_urlBuilder = $urlBuilder;
    }

    /**
     * Build final URL from redirect entry
     *
     * @param $redirectUrl
     */
    public function buildUrl($redirectUrl)
    {
        if ($redirectUrl == '/') {
            $redirectUrl = '';
        }
        return $this->_urlBuilder->getUrl('', array('_direct' => $redirectUrl));
    }

    public function isCurrent($redirectUrl)
    {
        return $this->buildUrl($redirectUrl) == $this->_urlBuilder->getCurrentUrl();
    }

}