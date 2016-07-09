<?php
namespace Mestrona\CategoryRedirect\Test\Unit;

use Magento\Framework\App\Area;

class CatalogAttributesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Catalog\Model\Category
     */
    protected $category;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\Tree
     */
    protected $tree;

    /**
     * @var \Magento\Catalog\Helper\Category
     */
    protected $categoryHelper;

    /**
     * @var string
     */
    protected $uniq;

    /**
     * @var string
     */
    protected $customUrl;

    protected function setUp()
    {
        /**
         * Create category
         * @source \Magento\Catalog\Model\Indexer\FlatTest::testCreateCategory
         */

        /** @var \Magento\Catalog\Model\Category $category */
        $category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Catalog\Model\Category'
        );
        $category->getResource()->load($category, 2);

        /** @var \Magento\Catalog\Model\Category $categoryOne */
        $categoryOne = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Catalog\Model\Category'
        );
        $this->uniq = uniqid();
        $this->customUrl = 'foo-' . $this->uniq . 'bar';
        $categoryOne
            ->setName('Home Category ' . $this->uniq)->setPath($category->getPath())
            ->setIsActive(true)
            ->setRedirectUrl($this->customUrl);
        $category->getResource()->save($categoryOne);

        $this->category = $categoryOne;

        $this->tree = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Catalog\Model\ResourceModel\Category\Tree'
        );

        $this->categoryHelper = $this->tree = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Catalog\Helper\Category'
        );
    }

    public function testAttributeInstalled()
    {
        /**
         * @var $attribute \Magento\Catalog\Model\Category\Attribute
         */
        $attribute = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            '\Magento\Catalog\Model\Category\Attribute'
        );
        $attribute->loadByCode('catalog_category', 'redirect_url');
        $this->assertNotNull($attribute->getId());
    }

    public function testRedirectUrlAttributeIsInCollection()
    {
        $collection = $this->tree->getCollection()->addAttributeToFilter('entity_id' , $this->category->getId());
        $this->assertEquals($this->customUrl, $collection->getFirstItem()->getRedirectUrl());
    }

    /**
     * Check the helper itself
     */
    public function testGetCategoryUrl()
    {
        $url = $this->categoryHelper->getCategoryUrl($this->category);
        $this->assertEquals('http://localhost/index.php/' . $this->customUrl, $url);
    }

    /**
     * Check if menu items have the right URL
     *
     * @magentoAppArea frontend
     */
    public function testUrlInMenu()
    {
        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\Framework\View\DesignInterface'
        )->setDesignTheme(
            'Magento/blank'
        );

        /**
         * @var $layout \Magento\Framework\View\LayoutInterface
         */
        $layout = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Framework\View\LayoutInterface::class
        );

        $block = $layout->addBlock(\Magento\Theme\Block\Html\Topmenu::class, 'test');
        $block->setTemplate('Magento_Theme::html/topmenu.phtml');

        $result = $block->toHtml();
        $this->assertContains('http://localhost/index.php/' . $this->customUrl, $result);
    }
}
