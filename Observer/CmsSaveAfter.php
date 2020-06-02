<?php
namespace RedChamps\CmsDataSync\Observer;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use RedChamps\CmsDataSync\Model\ProcessorFactory;

class CmsSaveAfter implements ObserverInterface
{
    protected $request;

    protected $processorFactory;

    protected $messageManager;

    public function __construct(
        RequestInterface $request,
        ProcessorFactory $processorFactory,
        ManagerInterface $messageManager
    ) {
        $this->request = $request;
        $this->processorFactory = $processorFactory;
        $this->messageManager = $messageManager;
    }

    public function execute(Observer $observer)
    {
        if ($this->request->getParam('action') == 'sync') {
            $entity = $observer->getObject();
            $response = $this->processorFactory
                ->create($this->request->getParam('type'))
                ->process($entity->getId());
            if (isset($response['success']) && $response['success']) {
                $this->messageManager->addSuccessMessage(__("Synced Successfully."));
            } elseif (isset($response['message'])) {
                $this->messageManager->addErrorMessage($response['message']);
            }
        }
    }
}
