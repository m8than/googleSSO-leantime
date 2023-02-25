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
            width: 100%;
            height: 29px;
            background-color: #fff;
            border-radius: var(--box-radius);
            border: 1px solid var(--main-border-color);
            margin: 0 auto;
        }
        .google-btn .google-icon-wrapper {
            position: absolute;
            margin-top: 1px;
            margin-left: 3px;
            width: 28px;
            height: 26px;
            border-radius: 2px;
            background-color: #fff;
        }
        .google-btn .google-icon {
            width: 20px;
            height: 20px;
            margin: 3px !important;
        }
        .google-btn .btn-text {
            margin: 3px 8px 0 0;
            color: #555;
            font-size: 14px;
            letter-spacing: 0.2px;
            font-family: var(--primary-font-family);
            /* padding-left: 38px; */
            text-align: center;
        }
        .google-btn:hover {
            border-color:var(--primary-color);
            cursor:pointer;
        }
        .google-btn:active {
            background: var(--primary-color);

        }

        .google-btn:active .btn-text {
            color:#fff;
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
