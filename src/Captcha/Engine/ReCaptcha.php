<?php

/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Check google recaptcha
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *        
 */
class Captcha_Engine_ReCaptcha extends Captcha_Engine
{

    /**
     * This is our default secret which is an valid google reCAPTCHA secure
     * code.
     * this is used to test system. You are free to use it as default
     * system captcha system.
     *
     * @var string
     */
    const SECRET_DEFAULT = '6LeuFzkUAAAAAEIIggHSQUNlTkiJ8UVXLMsHoH3s';
    const SECRET_ANDROID_DEFAULT = '6LcnvlkUAAAAAOfxhviySyLGNH0CEmVV65RL4ZHQ';

    /**
     * Secret key identitifier
     *
     * @var string
     */
    const SECRET_KEY = 'captcha.engine.recaptcha.secret';
    const TOKEN_KEY = 'g_recaptcha_response';
    
    const SECRET_ANDROID_KEY = 'captcha.engine.recaptcha.android.secret';
    const TOKEN_ANDROID_KEY = 'g_recaptcha_android_response';

    /**
     *
     * {@inheritdoc}
     * @see Captcha_Engine::verify()
     */
    public function verify($request)
    {
        // load key and token
        if(array_key_exists(self::TOKEN_KEY, $request->REQUEST)){
            $secret = parent::getProperty(self::SECRET_KEY, self::SECRET_DEFAULT);
            $token = $request->REQUEST[self::TOKEN_KEY];
        } else if(array_key_exists(self::TOKEN_ANDROID_KEY, $request->REQUEST)){
            $secret = parent::getProperty(self::SECRET_ANDROID_KEY, self::SECRET_ANDROID_DEFAULT);
            $token = $request->REQUEST[self::TOKEN_ANDROID_KEY];
        } else {
            // TODO: maos, 2018: throw 404 error
            throw new Pluf_Exception_MismatchParameter('recaptcha token not found');
        }
        // Try to workaround locked down web servers.
        if (! ini_get('allow_url_fopen')) {
            // allow_url_fopen = Off
            $recaptcha = new \ReCaptcha\ReCaptcha($secret, new \ReCaptcha\RequestMethod\SocketPost());
        } else {
            // allow_url_fopen = On
            $recaptcha = new \ReCaptcha\ReCaptcha($secret);
        }
        $resp = $recaptcha->verify($token, $request->remote_addr);
        return $resp->isSuccess();
    }
}