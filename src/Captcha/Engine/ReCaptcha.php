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

    /**
     * Secret key identitifier
     *
     * @var string
     */
    const SECRET_KEY = 'captcha.engine.recaptcha.secret';

    /**
     *
     * {@inheritdoc}
     * @see Captcha_Engine::verify()
     */
    public function verify($request)
    {
        $secret = parent::getProperty(Captcha_Engine_ReCAPTCHA::SECRET_KEY, Captcha_Engine_ReCAPTCHA::DEFAULT_SECRET);
        $recaptcha = new \ReCaptcha\ReCaptcha($secret);
        $resp = $recaptcha->verify($request->REQUEST['g-recaptcha-response'], $remoteIp);
        return $resp->isSuccess();
    }
}