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
class Captcha_Middleware_Verifier implements Pluf_Middleware
{

    /**
     *
     * {@inheritdoc}
     * @see Pluf_Middleware::process_request()
     */
    function process_request(&$request)
    {
        // No need recaptch for users
        if (! ($request->user == null || $request->user->isAnonymous())) {
            return false;
        }
        // READ methods
        $methods = array(
            'GET',
            'HEAD'
        );
        if (in_array($request->method, $methods)) {
            return false;
        }
        // maso, 2017: load engine and verify request
        $type = Tenant_Service::setting('captcha.engine', 'nocaptcha');
        $engine = Captcha_Service::getEngine($type);
        if (! $engine->verify($request)) {
            throw new Captcha_Exception_CaptchaRequired();
        }
        return false;
    }

    /**
     *
     * {@inheritdoc}
     * @see Pluf_Middleware::process_response()
     */
    public function process_response($request, $response)
    {
        return $response;
    }
}
