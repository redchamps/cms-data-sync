<?php
namespace RedChamps\CmsDataSync\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class ConfigReader
{
    const XML_BASE_PATH = "cms_data_sync/connection/";

    protected $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getTargetWebsiteName()
    {
        return $this->getConfig('site_name');
    }

    public function getConfig($path)
    {
        return $this->scopeConfig->getValue(self::XML_BASE_PATH . $path);
    }
}
