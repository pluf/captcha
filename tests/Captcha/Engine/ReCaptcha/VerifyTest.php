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
namespace Pluf\Test\Captcha\Engine\ReCaptcha;

use Pluf\Captcha;
use Pluf\Exception;
use Pluf\Test\Client;
use Pluf\Test\TestCase;
use Pluf;
use Pluf_HTTP_Request;
use Pluf_Migration;
use Pluf_Tenant;
use User_Account;
use User_Credential;
use User_Role;

class VerifyTest extends TestCase
{

    private static $tenant = null;

    private static $user = null;

    /**
     *
     * @beforeClass
     */
    public static function installApps()
    {
        Pluf::start(__DIR__ . '/../../../conf/config.php');
        $m = new Pluf_Migration(Pluf::f('installed_apps'));
        $m->install();
        // Test user
        $user = new User_Account();
        $user->login = 'test';
        $user->is_active = true;
        if (true !== $user->create()) {
            throw new Exception();
        }
        // Credential of user
        $credit = new User_Credential();
        $credit->setFromFormData(array(
            'account_id' => $user->id
        ));
        $credit->setPassword('test');
        if (true !== $credit->create()) {
            throw new Exception();
        }

        $per = User_Role::getFromString('tenant.owner');
        $user->setAssoc($per);
        self::$user = $user;

        // Test tenant
        self::$tenant = new Pluf_Tenant();
        self::$tenant->domain = 'localhost';
        self::$tenant->subdomain = 'test';
        self::$tenant->validate = true;
        if (true !== self::$tenant->create()) {
            throw new  \Pluf\Exception('Faile to create new tenant');
        }
    }

    /**
     *
     * @afterClass
     */
    public static function uninstallApps()
    {
        $m = new Pluf_Migration(Pluf::f('installed_apps'));
        $m->unInstall();
    }

    /**
     *
     * @test
     */
    public function testAssertRequest()
    {
        $e = new Captcha\Engine\ReCaptcha();
        $this->assertNotNull($e);
        $this->assertTrue($e instanceof Captcha\Engine);

        $client = new Client();
        $this->assertNotNull($client);

        $request = new Pluf_HTTP_Request("/");
        $request->method = 'POST';
        $request->REQUEST['g_recaptcha_response'] = 'testtooken';
        $this->assertFalse($e->verify($request));
    }

    /**
     *
     * @test
     */
    public function testAndroidAssertRequest()
    {
        $e = new Captcha\Engine\ReCaptcha();
        $this->assertNotNull($e);
        $this->assertTrue($e instanceof Captcha\Engine);

        $client = new Client();
        $this->assertNotNull($client);

        $request = new Pluf_HTTP_Request("/");
        $request->method = 'POST';
        $request->REQUEST['g_recaptcha_android_response'] = 'testtooken';
        $this->assertFalse($e->verify($request));
    }
}

