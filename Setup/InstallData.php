<?php

namespace Mestrona\CategoryRedirect\Setup;

use Magento\Catalog\Model\Indexer\Category\Flat\State;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Indexer\IndexerRegistry;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Flat category indexer state
     *
     * @var State
     */
    protected $flatState;

    /**
     * Indexer registry
     *
     * @var IndexerRegistry
     */
    protected $indexerRegistry;

    /**
     * @param EavSetupFactory $eavSetupFactory
     * @param State $flatState
     * @param IndexerRegistry $indexerRegistry
     */
    public function __construct(EavSetupFactory $eavSetupFactory, State $flatState, IndexerRegistry $indexerRegistry)
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->flatState = $flatState;
        $this->indexerRegistry = $indexerRegistry;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        /**
         * Add attributes to the eav/attribute
         */
        $eavSetup->removeAttribute(\Magento\Catalog\Model\Category::ENTITY, 'redirect_url');
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'redirect_url',
            [
                'type' => 'varchar',
                'group' => 'General',
                'label' => 'Redirect to another URL',
                'input' => 'text',
                'required' => false,
                'sort_order' => 2,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => true,
                'visible_on_front' => true,
            ]
        );

        if ($this->flatState->isFlatEnabled()) {
            $this->indexerRegistry->get(State::INDEXER_ID)->invalidate();
        }
    }
}
