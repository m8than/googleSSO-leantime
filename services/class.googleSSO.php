<?php

namespace leantime\plugins\services;

require_once __DIR__ . "/../vendor/autoload.php";

use leantime\domain\services\setting;
use Google\Client;


class googleSSO
{
    /**
     * @var Client
     */
	private $googleClient;

    /**
     * @var setting
     */
    private $settings;

    public function __construct()
    {
        $this->settings = new setting();
        $this->googleClient = new Client();
        $this->googleClient->setClientId($this->settings->getSetting("googleSSO.clientId"));
        $this->googleClient->setClientSecret($this->settings->getSetting("googleSSO.clientSecret"));
        $this->googleClient->setRedirectUri(BASE_URL .'/');
        $this->googleClient->setScopes('email');
    }

    public function attemptLogin($data)
    {
        $token = null;
        if (isset($data['code'])) {
            $token = $this->googleClient->fetchAccessTokenWithAuthCode($data['code']);
        }
        
        if (!empty($token) && isset($token['id_token'])) {
            $this->googleClient->setAccessToken($token);
            if ($this->googleClient->getAccessToken()) {
                $token_data = $this->googleClient->verifyIdToken();

                $email = $token_data['email'];

                if (strpos($email, $this->settings->getSetting("googleSSO.emailDomain")) !== false) {
                    $userRepo = new \leantime\domain\repositories\users();
                    return $userRepo->getUserByEmail($email);
                }
            }
        }

        return false;
    }


    public function createAuthUrl()
    {
        return $this->googleClient->createAuthUrl();
    }
}

