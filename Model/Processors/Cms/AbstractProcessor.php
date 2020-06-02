<?php
namespace RedChamps\CmsDataSync\Model\Processors\Cms;

use RedChamps\CmsDataSync\Model\Api;
use RedChamps\CmsDataSync\Model\Entity\RemoteExistenceChecker;

class AbstractProcessor
{
    protected $remoteExistenceChecker;

    protected $api;

    public function __construct(
        RemoteExistenceChecker $remoteExistenceChecker,
        Api $api
    ) {
        $this->api = $api;
        $this->remoteExistenceChecker = $remoteExistenceChecker;
    }

    public function execute($entity, $apiPath, $data)
    {
        $result = [
            "success" => false,
            "response" => ""
        ];
        $requestType = "POST";
        if ($pageId = $this->remoteExistenceChecker->execute($entity->getIdentifier(), $apiPath)) {
            $requestType = "PUT";
            $apiPath = $apiPath . $pageId;
        }
        try {
            $response = $this->api->connect(
                $requestType,
                $apiPath,
                $data
            );
            if ($response && $response->getStatusCode() == 200) {
                $result['success'] = true;
                $result['response'] = $response;
            } else {
                $decoded = @json_decode($response, true);
                $result['response'] = $decoded ? $decoded : "";
            }
        } catch (\Exception $exception) {
            $result['response'] = $exception->getMessage();
        }
        return $result;
    }
}
