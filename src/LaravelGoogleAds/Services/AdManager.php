<?php

namespace LaravelGoogleAds\Services;

use Google\AdsApi\AdManager\AdManagerServices;
use Google\AdsApi\Common\AdsSoapClient;
use Google\AdsApi\Common\Configuration;
use Google\AdsApi\Common\OAuth2TokenBuilder;
use Google\AdsApi\AdManager\AdManagerSession;
use Google\AdsApi\AdManager\AdManagerSessionBuilder;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\Credentials\UserRefreshCredentials;

class AdManagerService
{
    protected $config;

    public function __construct($configOverrides = [])
    {
        $this->config = $configOverrides;
    }

    /**
     * Get service
     *
     * @param string $serviceClass
     * @return AdsSoapClient
     */
    public function getService($serviceClass)
    {
        $dfpServices = new AdManagerServices();

        $session = $this->session();

        return $dfpServices->get($session, $serviceClass);
    }

    /**
     * Create a new session
     *
     * @return DfpSession|mixed
     */
    public function session()
    {
        return ((new AdManagerSessionBuilder())
            ->from($this->configuration())
            ->withOAuth2Credential($this->oauth2Credentials())
            ->build());
    }

    /**
     * oAuth2 credentials
     * @return ServiceAccountCredentials|UserRefreshCredentials|mixed
     */
    private function oauth2Credentials()
    {
        return (new OAuth2TokenBuilder())
            ->from($this->configuration())
            ->build();
    }

    /**
     * Configuration
     *
     * @return Configuration
     */
    private function configuration()
    {
        $config = config('google-ads');

        $config = array_merge_recursive($config, $this->config);

        return new Configuration($config);
    }
}
