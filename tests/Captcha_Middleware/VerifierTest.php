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
 *
 * @author maso
 */
class Captcha_Middleware_VerifierTest extends TestCase
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
    public function testClassInstance()
    {
        $v = new Captcha_Middleware_Verifier();
        Test_Assert::assertNotNull($v);
        Test_Assert::assertTrue($v instanceof Pluf_Middleware);
    }

    /**
     * @test
     */
    public function testMethodRequest()
    {
        $client = new Test_Client(array());
        Test_Assert::assertNotNull($client);
        
        $request = new Pluf_HTTP_Request("/");
        $request->REQUEST['g-recaptcha-response'] = 'testtooken';
        
        $v = new Captcha_Middleware_Verifier();
        Test_Assert::assertNotNull($v);
        Test_Assert::assertTrue($v instanceof Pluf_Middleware);
        
        $methods = array(
            'GET',
            'HEAD'
        );
        foreach ($methods as $method) {
            $request->method = $method;
            Test_Assert::assertFalse($v->process_request($request));
        }
    }

    /**
     * @test
     */
    public function testَUserRequest()
    {
        $client = new Test_Client(array());
        Test_Assert::assertNotNull($client);
        
        $request = new Pluf_HTTP_Request("/");
        $request->REQUEST['g-recaptcha-response'] = 'testtooken';
        $request->user = self::$user;
        
        $v = new Captcha_Middleware_Verifier();
        Test_Assert::assertNotNull($v);
        Test_Assert::assertTrue($v instanceof Pluf_Middleware);
        
        $methods = array(
            'POST',
            'DELETE'
        );
        foreach ($methods as $method) {
            $request->method = $method;
            Test_Assert::assertFalse($v->process_request($request));
        }
    }

    /**
     * @test
     */
    public function testَNoCaptchaRequest()
    {
        $client = new Test_Client(array());
        Test_Assert::assertNotNull($client);
        
        $request = new Pluf_HTTP_Request("/");
        $request->REQUEST['g-recaptcha-response'] = 'testtooken';
        
        $v = new Captcha_Middleware_Verifier();
        Test_Assert::assertNotNull($v);
        Test_Assert::assertTrue($v instanceof Pluf_Middleware);
        
        Tenant_Service::setSetting('captcha.engine', 'nocaptcha');
        $methods = array(
            'POST',
            'DELETE'
        );
        foreach ($methods as $method) {
            $request->method = $method;
            Test_Assert::assertFalse($v->process_request($request));
        }
    }

    /**
     * @expectedException Captcha_Exception_CaptchaRequired
     * @test
     */
    public function testَReCaptchaRequest()
    {
        $client = new Test_Client(array());
        Test_Assert::assertNotNull($client);
        
        $request = new Pluf_HTTP_Request("/");
        $request->REQUEST['g-recaptcha-response'] = 'testtooken';
        
        $v = new Captcha_Middleware_Verifier();
        Test_Assert::assertNotNull($v);
        Test_Assert::assertTrue($v instanceof Pluf_Middleware);
        
        Tenant_Service::setSetting('captcha.engine', 'recaptcha');
        $methods = array(
            'POST',
            'DELETE'
        );
        foreach ($methods as $method) {
            $request->method = $method;
            Test_Assert::assertFalse($v->process_request($request));
        }
    }
}



