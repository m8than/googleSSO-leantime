<?php
namespace leantime\plugins\controllers;

require_once "../vendor/autoload.php";

use leantime\core;
use leantime\core\controller;
use leantime\domain\services\setting;
use leantime\domain\services\auth;
use leantime\domain\models\auth\roles;


class settings extends controller
{	
    /**
     * @var setting
     */
    private $settings;

    public function init()
    {
        $this->settings = new setting();
    }

    /**
     * @return void
     */
    public function get()
    {
        auth::authOrRedirect([roles::$owner, roles::$admin], true);
        
        $this->tpl->assign('clientId', $this->settings->getSetting("googleSSO.clientId"));
        $this->tpl->assign('clientSecret', $this->settings->getSetting("googleSSO.clientSecret"));
        $this->tpl->assign('emailDomain', $this->settings->getSetting("googleSSO.emailDomain"));
        $this->tpl->display("googleSSO.settings");
    }

    public function post($params)
    {
        auth::authOrRedirect([roles::$owner, roles::$admin], true);
        
        $settingSvc = new \leantime\domain\services\setting();

        if(isset($params['saveSettings'])) {
            $settingSvc->saveSetting("googleSSO.clientId", $params['clientId']);
            $settingSvc->saveSetting("googleSSO.clientSecret", $params['clientSecret']);
            $settingSvc->saveSetting("googleSSO.emailDomain", $params['emailDomain']);
        }

        $this->tpl->setNotification("Settings saved", "success");
        $this->tpl->redirect(BASE_URL."/googleSSO/settings");
    }
}

