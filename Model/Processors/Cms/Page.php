<?php
namespace RedChamps\CmsDataSync\Model\Processors\Cms;

use Magento\Cms\Model\PageRepository;
use Magento\Cms\Api\Data\PageInterface;
use RedChamps\CmsDataSync\Api\ProcessorInterface;

class Page implements ProcessorInterface
{
    protected $pageRepository;

    protected $abstractProcessor;

    public function __construct(
        PageRepository $pageRepository,
        AbstractProcessor $abstractProcessor,
        array $data = []
    ) {
        $this->abstractProcessor = $abstractProcessor;
        $this->pageRepository = $pageRepository;
    }

    public function process($id)
    {
        $result = [
            "success" => false,
            "response" => ""
        ];
        $apiPath = "/rest/V1/cmsPage/";
        $entity = $this->getPage($id);
        if ($entity) {
            $data = ["page" => [
                PageInterface::IDENTIFIER => $entity->getIdentifier(),
                PageInterface::TITLE => $entity->getTitle(),
                PageInterface::PAGE_LAYOUT => $entity->getPageLayout(),
                PageInterface::META_TITLE => $entity->getMetaTitle(),
                PageInterface::META_KEYWORDS => $entity->getMetaKeywords(),
                PageInterface::META_DESCRIPTION => $entity->getMetaDescription(),
                PageInterface::CONTENT_HEADING => $entity->getContentHeading(),
                PageInterface::CONTENT => $entity->getContent(),
                PageInterface::CREATION_TIME => $entity->getCreationTime(),
                PageInterface::UPDATE_TIME => $entity->getUpdateTime(),
                PageInterface::SORT_ORDER => $entity->getSortOrder(),
                PageInterface::LAYOUT_UPDATE_XML => $entity->getLayoutUpdateXml(),
                PageInterface::CUSTOM_THEME => $entity->getCustomTheme(),
                PageInterface::CUSTOM_ROOT_TEMPLATE => $entity->getCustomRootTemplate(),
                PageInterface::CUSTOM_LAYOUT_UPDATE_XML => $entity->getCustomLayoutUpdateXml(),
                PageInterface::CUSTOM_THEME_FROM => $entity->getCustomThemeFrom(),
                PageInterface::CUSTOM_THEME_TO => $entity->getCustomThemeTo()
            ]];
            $result = $this->abstractProcessor->execute($entity, $apiPath, $data);
        } else {
            $result['response'] = __("Couldn't load CMS page content.");
        }
        return $result;
    }

    protected function getPage($id)
    {
        try {
            return $this->pageRepository->getById($id);
        } catch (\Exception $exception) {
            //do nothing
        }
        return false;
    }
}
