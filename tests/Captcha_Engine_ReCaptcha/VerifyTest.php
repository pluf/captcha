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
use PHPUnit\Framework\TestCase;
require_once 'Pluf.php';

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class Captcha_Engine_ReCaptcha_VerifyTest extends TestCase
{

    private static $tenant = null;

    private static $user = null;

    /**
     * @beforeClass
     */
    public static function installApps()
    {
        Pluf::start(dirname(__FILE__) . '/../conf/config.mysql.php');
        $m = new Pluf_Migration(array(
            'Pluf',
            'User',
            'Setting',
            'Captcha'
        ));
        $m->install();
        // Test user
        self::$user = new User();
        self::$user->login = 'test';
        self::$user->first_name = 'test';
        self::$user->last_name = 'test';
        self::$user->email = 'toto@example.com';
        self::$user->setPassword('test');
        self::$user->active = true;
        self::$user->administrator = true;
        if (true !== self::$user->create()) {
            throw new Exception();
        }
        
        // Test tenant
        self::$tenant = new Pluf_Tenant();
        self::$tenant->domain = 'localhost';
        self::$tenant->subdomain = 'test';
        self::$tenant->validate = true;
        if (true !== self::$tenant->create()) {
            throw new Pluf_Exception('Faile to create new tenant');
        }
    }

    /**
     * @afterClass
     */
    public static function uninstallApps()
    {
        $m = new Pluf_Migration(array(
            'Pluf',
            'User',
            'Setting',
            'Captcha'
        ));
        $m->unInstall();
    }

    /**
     * @test
     */
    public function testAssertRequest()
    {
        $e = new Captcha_Engine_ReCaptcha();
        Test_Assert::assertNotNull($e);
        Test_Assert::assertTrue($e instanceof Captcha_Engine);
        
        $client = new Test_Client(array());
        Test_Assert::assertNotNull($client);
        
        $request = new Pluf_HTTP_Request("/");
        $request->method = 'POST';
        $request->REQUEST['g-recaptcha-response'] = 'testtooken';
        Test_Assert::assertFalse($e->verify($request));
    }
}

