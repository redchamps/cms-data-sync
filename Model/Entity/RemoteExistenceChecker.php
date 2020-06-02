<?php
namespace RedChamps\CmsDataSync\Model\Entity;

use RedChamps\CmsDataSync\Model\Api;

class RemoteExistenceChecker
{
    protected $api;

    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    public function execute($identifier, $apiPath)
    {
        try {
            $response = $this->api->connect(
                "GET",
                $apiPath . "byIdentifier/" . $identifier
            );
            if ($response && $response->getStatusCode() == 200) {
                $data = $response->getBody();
                $pageData = json_decode($data, true);
                return isset($pageData['id']) ? $pageData['id'] : false;
            }
        } catch (\Exception $exception) {
            //do nothing
        }
        return  false;
    }
}
