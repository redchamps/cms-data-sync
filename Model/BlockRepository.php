<?php
namespace RedChamps\CmsDataSync\Model;

use Magento\Cms\Model\BlockFactory;
use Magento\Cms\Model\ResourceModel\Block as BlockResource;
use Magento\Framework\Exception\NoSuchEntityException;
use RedChamps\CmsDataSync\Api\BlockRepositoryInterface;

/**
 * Class PageRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class BlockRepository implements BlockRepositoryInterface
{
    protected $blockFactory;

    protected $blockResource;

    public function __construct(BlockFactory$blockFactory, BlockResource $blockResource)
    {
        $this->blockFactory = $blockFactory;
        $this->blockResource = $blockResource;
    }

    public function getByIdentifier($identifier)
    {
        $block = $this->blockFactory->create();
        $this->blockResource->load($block, $identifier, "identifier");
        if (!$block->getId()) {
            throw new NoSuchEntityException(__('The CMS block with the "%1" identified doesn\'t exist.', $identifier));
        }
        return $block;
    }
}
