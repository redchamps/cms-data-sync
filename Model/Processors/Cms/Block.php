<?php
namespace RedChamps\CmsDataSync\Model\Processors\Cms;

use Magento\Cms\Model\BlockRepository;
use Magento\Cms\Api\Data\BlockInterface;
use RedChamps\CmsDataSync\Api\ProcessorInterface;

class Block implements ProcessorInterface
{
    protected $blockRepository;

    protected $abstractProcessor;

    public function __construct(
        BlockRepository $blockRepository,
        AbstractProcessor $abstractProcessor,
        array $data = []
    ) {
        $this->abstractProcessor = $abstractProcessor;
        $this->blockRepository = $blockRepository;
    }

    public function process($id)
    {
        $result = [
            "success" => false,
            "response" => ""
        ];
        $apiPath = "/rest/V1/cmsBlock/";
        $entity = $this->getBlock($id);
        if ($entity) {

            $data = ["block" => [
                BlockInterface::IDENTIFIER => $entity->getIdentifier(),
                BlockInterface::TITLE => $entity->getTitle(),
                BlockInterface::CONTENT => $entity->getContent(),
                BlockInterface::CREATION_TIME => $entity->getCreationTime(),
                BlockInterface::UPDATE_TIME => $entity->getUpdateTime()
            ]];
            $result = $this->abstractProcessor->execute($entity, $apiPath, $data);
        } else {
            $result['response'] = __("Couldn't load CMS page content.");
        }
        return $result;
    }

    protected function getBlock($id)
    {
        try {
            return $this->blockRepository->getById($id);
        } catch (\Exception $exception) {
            //do nothing
        }
        return false;
    }
}
