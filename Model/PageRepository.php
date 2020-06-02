<?php
namespace RedChamps\CmsDataSync\Model;

use Magento\Cms\Model\PageFactory;
use Magento\Cms\Model\ResourceModel\Page as PageResource;
use Magento\Framework\Exception\NoSuchEntityException;
use RedChamps\CmsDataSync\Api\PageRepositoryInterface;

/**
 * Class PageRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class PageRepository implements PageRepositoryInterface
{
    protected $pageFactory;

    protected $pageResource;

    public function __construct(PageFactory $pageFactory, PageResource $pageResource)
    {
        $this->pageFactory = $pageFactory;
        $this->pageResource = $pageResource;
    }

    public function getByIdentifier($identifier)
    {
        $page = $this->pageFactory->create();
        $this->pageResource->load($page, $identifier, "identifier");
        if (!$page->getId()) {
            throw new NoSuchEntityException(__('The CMS page with the "%1" identified doesn\'t exist.', $identifier));
        }
        return $page;
    }
}
