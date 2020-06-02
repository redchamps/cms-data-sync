<?php
namespace RedChamps\CmsDataSync\Controller\Adminhtml\Action;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\JsonFactory;

class TestConnection extends Action
{
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    protected $api;

    public function __construct(
        Action\Context $context,
        JsonFactory $resultJsonFactory,
        \RedChamps\CmsDataSync\Model\Api $api
    ) {
        $this->api = $api;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = [
            'success' => false,
            'errorMessage' => '',
        ];

        $request = $this->getRequest();
        try {
            $response = $this->api->connect(
                \Zend\Http\Request::METHOD_GET,
                '/rest/V1/cmsBlock/1000',
                "",
                $request->getParam('consumer_key'),
                $request->getParam('consumer_secret'),
                $request->getParam('access_token'),
                $request->getParam('access_token_secret'),
                $request->getParam('url')
            );
            if ($response && $response->getStatusCode() != 401) {
                $result['success'] = true;
            }
        } catch (\Exception $exception) {
            $result['errorMessage'] = $exception->getMessage();
        }
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($result);
    }
}
