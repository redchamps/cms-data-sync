<?php
namespace RedChamps\CmsDataSync\Block\Adminhtml\Buttons;

use Magento\Backend\Block\Widget\Context;
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Block\Adminhtml\Block\Edit\GenericButton;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Store\Model\StoreManagerInterface;
use RedChamps\CmsDataSync\Model\ConfigReader;

class CmsBlock extends GenericButton implements ButtonProviderInterface
{
    protected $configReader;

    protected $_block;

    public function __construct(
        Context $context,
        BlockRepositoryInterface $blockRepository,
        ConfigReader $configReader
    ) {
        $this->configReader = $configReader;
        parent::__construct($context, $blockRepository);
    }

    /**
     * Return button attributes array
     */
    public function getButtonData()
    {
        $this->_block = $this->getBlock();
        if ($this->_block) {
            return [
                'label' => __('Sync to %1', $this->configReader->getTargetWebsiteName()),
                'on_click' => sprintf("location.href ='%s'", $this->getActionUrl()),
                'sort_order' => 0,
            ];
        }
    }

    protected function getActionUrl()
    {
        return $this->getUrl(
            'cms_data_sync/action/sync',
            [
                "type" => "block",
                "id" => $this->_block->getId()
            ]
        );
    }

    public function getBlock()
    {
        try {
            return $this->blockRepository->getById(
                $this->context->getRequest()->getParam('block_id')
            );
        } catch (NoSuchEntityException $e) {
        }
        return null;
    }
}
