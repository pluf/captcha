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
namespace Pluf\Test\Captcha\Engine\NoCaptcha;

use PHPUnit\Framework\TestCase;
use Pluf\Captcha;
use Pluf\Test\Client;
use Pluf;
use Pluf_HTTP_Request;
require_once 'Pluf.php';

/**
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 *
 * @author maso
 *        
 */
class Captcha_Engine_NoCaptcha_VerifyTest extends TestCase
{

    /**
     *
     * @before
     */
    public function setUpTest()
    {
        Pluf::start(__DIR__ . '/../../../conf/config.php');
    }

    /**
     *
     * @test
     */
    public function testAssertRequest()
    {
        $e = new Captcha\Engine\NoCaptcha();
        $this->assertNotNull($e);
        $this->assertTrue($e instanceof Captcha\Engine);

        $client = new Client();
        $this->assertNotNull($client);

        $request = new Pluf_HTTP_Request("/");
        $request->method = 'POST';
        $this->assertTrue($e->verify($request));
    }
}

