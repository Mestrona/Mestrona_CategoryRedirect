<?php
namespace Mestrona\CategoryRedirect\Test\Unit;

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
        $categoryOne->setName('Category ' . uniqid())->setPath($category->getPath())->setIsActive(true);
        $category->getResource()->save($categoryOne);

        $this->category = $categoryOne;

        $this->tree = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Catalog\Model\ResourceModel\Category\Tree'
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
        $collection = $this->tree->getCollection();
        $this->assertTrue($collection->getFirstItem()->hasData('redirect_url'));
    }
}
