<?php
namespace RedChamps\CmsDataSync\Controller\Adminhtml\Action;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use RedChamps\CmsDataSync\Model\ProcessorFactory;

class Sync extends Action
{
    protected $processorFactory;

    public function __construct(
        Action\Context $context,
        ProcessorFactory $processorFactory
    ) {
        $this->processorFactory = $processorFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $type = $this->getRequest()->getParam('type');
        $ids = $this->getRequest()->getParam("selected");
        if (!$ids && $this->getRequest()->getParam("id")) {
            $ids = [$this->getRequest()->getParam("id")];
        }
        if ($ids && $type) {
            $success = 0;
            $processor = $this->processorFactory->create($type, []);
            foreach ($ids as $id) {
                $result = $processor->process($id);
                if (isset($result['success']) && $result['success']) {
                    $success++;
                } elseif (isset($result['response'])) {
                    $this->messageManager->addErrorMessage($result['response']);
                }
            }
            if ($success) {
                $message = __("Synced successfully");
                if ($success > 1) {
                    $message = __("%1 item(s) have been successfully synced", $success);
                }
                $this->messageManager->addSuccessMessage($message);
            }
        } else {
            $this->messageManager->addErrorMessage(__("Some error occurred. Please try again"));
        }
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}
