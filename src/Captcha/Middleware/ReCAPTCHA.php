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
class Captcha_Middleware_ReCAPTCHA
{

    
    /**
     * Check request to detect bot
     * 
     * @param Pluf_HTTP_Request $request
     */
    function process_request (&$request)
    {
        // No need recaptch for users
        if(!($request->user == null || $request->user->isAnonymous())){
            return;
        }
        // READ methods
        $methods = array(
            'GET',
            'HEAD'
        );
        if(in_array($request->method, $methods)){
            return;
        }
        
        $secret = '6LeuFzkUAAAAAEIIggHSQUNlTkiJ8UVXLMsHoH3s';
        $recaptcha = new \ReCaptcha\ReCaptcha($secret);
        $resp = $recaptcha->verify($request->REQUEST['g-recaptcha-response'], $remoteIp);
        if ($resp->isSuccess()) {
            // verified!
            // if Domain Name Validation turned off don't forget to check hostname field
            // if($resp->getHostName() === $_SERVER['SERVER_NAME']) {  }
        } else {
            $errors = $resp->getErrorCodes();
        }
        
        return false;
    }
    
    
}
