<?php

/**
 * GoogleSSO
 *
 * Plugin to provide support for google single sign on.
 *
 */

function attemptLogin($params)
{
    if (!isset($params['redirect'])) return; // not a google login
    
    $query = parse_url($params['redirect'])['query'];
    parse_str($query, $data);

    if (!isset($data['code'])) return; // not a google login
    
    $googleSSO = new \leantime\plugins\services\googleSSO();
    $authService = \leantime\domain\services\auth::getInstance();
    $tpl = new \leantime\core\template();
    
    $user = $googleSSO->attemptLogin($data);

    if ($user) {
        // login user
        $authService->setUserSession($user);
        // redirect to dashboard
        $tpl->setNotification("Login successful", "success");
        $tpl->redirect(BASE_URL."/");            
    } else {
        // redirect to login page with error
        $tpl->setNotification("Login failed", "error");
        $tpl->redirect(BASE_URL."/login");
    }
}

//Create function for the event
function showGoogleLogin($payload)
{
    $googleSSO = new \leantime\plugins\services\googleSSO();
    $googleLoginUrl = $googleSSO->createAuthUrl();
    echo <<<LOGINHTML
        <style>
        .google-wrapper {
            width:100%;
        }
        .google-btn {
            width: 184px;
            height: 42px;
            background-color: #4285f4;
            border-radius: 2px;
            box-shadow: 0 3px 4px 0 rgba(0,0,0,.25);
            margin: 0 auto;
        }
        .google-btn .google-icon-wrapper {
            position: absolute;
            margin-top: 1px;
            margin-left: 1px;
            width: 40px;
            height: 40px;
            border-radius: 2px;
            background-color: #fff;
        }
        .google-btn .google-icon {
            width: 24px;
            height: 24px;
            margin: 8px !important;
        }
        .google-btn .btn-text {
            float: right;
            margin: 11px 11px 0 0;
            color: #fff;
            font-size: 14px;
            letter-spacing: 0.2px;
            font-family: "Roboto";
        }
        .google-btn:hover {
            box-shadow: 0 0 6px #4285f4;
            cursor:pointer;
        }
        .google-btn:active {
            background: #1669F2;
        }

        @import url(https://fonts.googleapis.com/css?family=Roboto:500);
        </style>
        <div class="google-wrapper">
            <div class="google-btn" onclick="window.location.href = '{$googleLoginUrl}';">
                <div class="google-icon-wrapper">
                    <img class="google-icon" src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg"/>
                </div>
                <p class="btn-text"><b>Sign in with google</b></p>
            </div>
        </div>
        <hr />
    LOGINHTML;
}

//Register event listener
\leantime\core\events::add_event_listener("core.template.tpl.auth.login.afterRegcontentOpen", 'showGoogleLogin');
\leantime\core\events::add_event_listener("domain.login.controllers.login.get.beforeAuth", 'attemptLogin');