<?php
namespace RedChamps\CmsDataSync\Block\Adminhtml\Buttons;

use Magento\Backend\Block\Widget\Context;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Cms\Block\Adminhtml\Page\Edit\GenericButton;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Store\Model\StoreManagerInterface;
use RedChamps\CmsDataSync\Model\ConfigReader;

class CmsPage extends GenericButton implements ButtonProviderInterface
{
    protected $configReader;

    protected $_page;

    public function __construct(
        Context $context,
        PageRepositoryInterface $pageRepository,
        ConfigReader $configReader
    ) {
        $this->configReader = $configReader;
        parent::__construct($context, $pageRepository);
    }

    /**
     * Return button attributes array
     */
    public function getButtonData()
    {
        $this->_page = $this->getPage();
        if ($this->_page) {
            return [
                'label' => __('Sync to %1', $this->configReader->getTargetWebsiteName()),
                'on_click' => sprintf("location.href='%s'", $this->getActionUrl())
            ];
        }
    }

    protected function getActionUrl()
    {
        return $this->getUrl(
            'cms_data_sync/action/sync',
            [
                "type" => "page",
                "id" => $this->_page->getId()
            ]
        );
    }

    public function getPage()
    {
        try {
            return $this->pageRepository->getById(
                $this->context->getRequest()->getParam('page_id')
            );
        } catch (NoSuchEntityException $e) {
        }
        return null;
    }
}
