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
 *        
 */
class Captcha_EngineServiceTest extends TestCase
{

    /**
     * @before
     */
    public function setUp()
    {
        Pluf::start(dirname(__FILE__) . '/../conf/config.mysql.php');
    }

    /**
     * @test
     */
    public function testClassInstance()
    {
        Test_Assert::assertTrue(method_exists('Captcha_Service', 'getEngine'));
        Test_Assert::assertTrue(method_exists('Captcha_Service', 'engines'));
    }

    /**
     * @test
     */
    public function testGetEngines()
    {
        $engList = Captcha_Service::engines();
        Test_Assert::assertNotNull($engList);
        Test_Assert::assertTrue(sizeof($engList) > 0);
    }

    /**
     * @test
     */
    public function testGetEngine()
    {
        $engList = Captcha_Service::engines();
        Test_Assert::assertNotNull($engList);
        Test_Assert::assertTrue(sizeof($engList) > 0);
        // Check engines
        foreach ($engList as $engine) {
            $et = Captcha_Service::getEngine($engine->getType());
            Test_Assert::assertNotNull($et);
            Test_Assert::assertTrue($et instanceof Captcha_Engine);
        }
    }
}

