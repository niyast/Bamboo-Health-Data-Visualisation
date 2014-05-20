<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Mobile Data Visualization</title>
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/css/style.css" />        
    </head>
    <body>

        <div id="container">
            <h1>Mobile Data Visualization</h1>

            <div id="body">
                <div style="margin-top: 7px;margin-right: 10px; text-align: center;">
                    <a href="" class="fb-login" style="margin: 5px auto 3% !important;"></a>
                    <?php
                    if (!empty($authUrl)) {
                        ?>
                        <a class='login' href='<?php echo $authUrl?>'><img src="<?php echo base_url() ?>public/images/google-login-button-asif18.png" alt="Google login using php api for your website" title="login with google" /></a>
                        <?php
                    } else {
                        ?>
                        <a class='logout' href='<?php echo base_url() ?>welcome/logout'>Logout</a>
                        <?php
                    }
                    ?>
                    <div>
                        <span style="padding: 10px; margin-bottom: 10px;" >OR</span>
                    </div>

                    <form name="loginNormal" method="post" action="">
                        <div>
                            <input id="" name="username" type="text" class="fld1" required="true" style="" placeholder="Username"/>
                        </div>
                        <div>
                            <input id="" name="password" type="password" class="fld1" required="true" style="" placeholder="Password"/>
                        </div>
                        <div>
                            <input  type="submit"  class="" style=" margin-top: -8px; " value="Login" name="login">
                        </div>                        

                    </form>
                </div>
            </div>

            <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
        </div>

    </body>
</html>