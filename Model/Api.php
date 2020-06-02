<?php
namespace RedChamps\CmsDataSync\Model;

use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlInterface;
use OAuth\Common\Consumer\Credentials;
use OAuth\Common\Http\Uri\Uri;
use OAuth\OAuth1\Signature\Signature;

use Zend\Http\Client;
use Zend\Http\Headers;
use Zend\Http\Request;

class Api
{
    protected $configReader;

    protected $messageManager;

    protected $url;

    public function __construct(
        ConfigReader $configReader,
        ManagerInterface $messageManager,
        UrlInterface $url
    ) {
        $this->configReader = $configReader;
        $this->url = $url;
        $this->messageManager = $messageManager;
    }

    public function connect(
        $requestType,
        $uri= null,
        $data = null,
        $consumerKey = null,
        $consumerSecret= null,
        $accessToken= null,
        $accessTokenSecret= null,
        $url= null
    ) {
        if (!$this->settingCheck()) {
            return false;
        }
        if (!$consumerKey) {
            $consumerKey = $this->configReader->getConfig('consumer_key');
        }
        if (!$consumerSecret) {
            $consumerSecret = $this->configReader->getConfig('consumer_secret');
        }
        if (!$accessToken) {
            $accessToken = $this->configReader->getConfig('access_token');
        }
        if (!$accessTokenSecret) {
            $accessTokenSecret = $this->configReader->getConfig('access_token_secret');
        }
        if (!$url) {
            $url = $this->configReader->getConfig('site_url');
        }

        $oauthSignatureMethod = 'HMAC-SHA1';
        $oauthTimestamp = time();
        $oauthNonce = md5(openssl_random_pseudo_bytes(20));

        // create oauth signature
        $params = [
            'oauth_consumer_key' => $consumerKey,
            'oauth_nonce' => $oauthNonce,
            'oauth_signature_method' => $oauthSignatureMethod,
            'oauth_timestamp' => $oauthTimestamp,
            'oauth_token' => $accessToken,
            'oauth_version' => '1.0',
        ];

        $credentials = new Credentials($consumerKey, $consumerSecret, $url);
        $signature = new Signature($credentials);
        $signature->setTokenSecret($accessTokenSecret);
        $signature->setHashingAlgorithm($oauthSignatureMethod);

        $restResourceUri = $url . $uri;
        $oauthUri = new Uri($restResourceUri);
        $oauthSignature = $signature->getSignature($oauthUri, $params, $requestType);
        $params['oauth_signature'] = $oauthSignature;

        // create oauth Authorization header
        $oauthHeader = 'OAuth ';
        foreach ($params as $key => $value) {
            $oauthHeader .= sprintf('%s="%s", ', $key, $value);
        }
        $oauthHeader = rtrim($oauthHeader, ' ,');

        $httpHeaders = new Headers();
        $httpHeaders->addHeaders([
            'Authorization' => $oauthHeader,
            'Content-Type' => 'application/json'
        ]);

        // create request for cms block using oauth Authorization header
        $request = new Request();
        $request->setHeaders($httpHeaders);
        $request->setUri($restResourceUri);
        $request->setMethod($requestType);

        // create client and get cms block response
        $client = new Client();
        $options = [
            'adapter'   => 'Zend\Http\Client\Adapter\Curl',
            'curloptions' => [CURLOPT_FOLLOWLOCATION => true],
            'maxredirects' => 0,
            'timeout' => 30
        ];
        $client->setRequest($request);
        if ($data) {
            $client->setRawBody(json_encode($data));
        }

        $client->setOptions($options);

        return $client->send();
    }

    protected function settingCheck()
    {
        if (!$this->configReader->getConfig('site_name')) {
            $settingUrl = $this->url->getUrl('adminhtml/system_config/edit', ['section'=>'cms_data_sync']);
            $this->messageManager->addErrorMessage(
                "The extension settings are not configured correctly. Please <a href='$settingUrl'>click here</a> to configure them."
            );
            return false;
        }
        return true;
    }
}
