<?php

/**
 * Verifier middleware
 * 
 * Must be used after session layer
 * 
 * See https://www.google.com/recaptcha/admin#site/339285934
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class Captcha_Middleware_Verifier
{

    /**
     * Check request to detect bot
     *
     * @param Pluf_HTTP_Request $request
     */
    function process_request(&$request)
    {
        // No need recaptch for users
        if (! ($request->user == null || $request->user->isAnonymous())) {
            return;
        }
        // READ methods
        $methods = array(
            'GET',
            'HEAD'
        );
        if (in_array($request->method, $methods)) {
            return;
        }
        // maso, 2017: load engine and verify request
        $type = Setting_Service::get("captcha.engine", "ReCaptcha");
        $engine = Captcha_Service::getEngine($type);
        if (! $engine->verify($request)) {
            throw new Captcha_Exception_CaptchaRequired();
        }
        return false;
    }
}
