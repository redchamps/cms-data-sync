<?php
namespace RedChamps\CmsDataSync\Model;

use Magento\Framework\ObjectManagerInterface;
use RedChamps\CmsDataSync\Api\ProcessorInterface;

class ProcessorFactory
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var string[]
     */
    private $processors;

    public function __construct(
        ObjectManagerInterface $objectManager,
        array $processors
    ) {
        $this->objectManager = $objectManager;
        $this->processors = $processors;
    }

    public function create(string $processor, array $arguments = []): ProcessorInterface
    {
        return $this->objectManager->create($this->processors[$processor], $arguments);
    }
}
