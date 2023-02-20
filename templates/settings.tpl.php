<?php
    $clientId = $this->get('clientId');
    $clientSecret = $this->get('clientSecret');
    $emailDomain = $this->get('emailDomain');
?>
<div class="pageheader">
    <div class="pageicon"><span class="fa fa-book"></span></div>
    <div class="pagetitle">


        <h1>Google SSO settings</h1>

    </div>

</div>

<div class="maincontent">

    <div class="maincontentinner">
        <?php echo $this->displayNotification(); ?>
        <h5 class="subtitle">Google SSO Settings</h5>

        <form class="" method="post" id="">
            <br />
            <p>These are google client settings to configure google sign-on</p>
            <br />
            <input type="hidden" value="1" name="saveSettings">

            <h4 class="widgettitle title-light"><span class="fa fa-google"></span>Google Settings</h4>
            <div class="row">
                <div class="col-md-2">
                    <label>Client ID</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="clientId" id="clientId" value="<?=$clientId?>" class="pull-left" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <label>Client Secret</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="clientSecret" id="clientSecret" value="<?=$clientSecret?>" class="pull-left" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <label>Restrict Email Domain</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="emailDomain" id="emailDomain" value="<?=$emailDomain?>" class="pull-left" />
                    <small>The email domain that you want to allow to login.</small>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <label>Redirect URI</label>
                </div>
                <div class="col-md-8">
                    <span><?=BASE_URL ?>/</span>
                </div>
            </div>

            <input type="submit" value="Save" id="saveBtn">
        </form>
    </div>

</div>


