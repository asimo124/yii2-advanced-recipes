<?php

/*
// this CustomAuthroizeFilter is just used to catch
// old cached clients using e2lstudio instead of esuite.
*/

namespace common\components;

use Yii;

class CustomAuthorizeFilter extends \conquer\oauth2\AuthorizeFilter
{
    
    public function __construct()
    {
        $getRequest = Yii::$app->request->get();
        if(isset($getRequest['redirect_uri'])) {
            $redirectUri = urldecode($getRequest['redirect_uri']);
            if($redirectUri == 'https://e2lstudio.engage2learn.org/index.html') {
                header('Location: https://esuite.engage2learn.org', true, 302);
                exit;
            }
            if($redirectUri == 'https://qa.e2lstudio.engage2learn.org/index.html') {
                header('Location: https://qa.esuite.engage2learn.org', true, 302);
                exit;
            }
        }
    }
}