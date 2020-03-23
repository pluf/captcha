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
namespace Pluf\Captcha;

use Pluf\ModelUtils;
use JsonSerializable;
use Pluf_HTTP_Request;
use Tenant_Service;

abstract class Engine implements JsonSerializable
{

    const ENGINE_PREFIX = 'Engine';

    /**
     * Get type of the engine
     *
     * @return string
     */
    public function getType()
    {
        $ref = new \ReflectionObject($this);
        $name = strtolower($ref->getShortName());
        return $name;
    }

    /**
     * Getting property of the captcha engine
     *
     * Engine can read properties to init the execution of the verification process. The property
     * is read only value and the engine itself are not able to edit.
     *
     * <code><pre>
     * $secure = parent::getProperty("reCAPTHCA.secureCode");
     * if($secure == NULL){
     * throw new \Pluf\Exception("Secure code is not set");
     * }
     * </pre></code>
     *
     *
     * @param string $key
     * @param string $default
     */
    protected function getProperty($key, $default = NULL)
    {
        return Tenant_Service::setting($key, $default);
    }

    /**
     *
     * @return string
     */
    public function getSymbol()
    {
        return $this->getType();
    }

    /**
     *
     * @return string
     */
    public function getTitle()
    {
        return '';
    }

    /**
     *
     * @return string
     */
    public function getDescription()
    {
        return '';
    }

    /**
     * Verify the request
     *
     *
     * @param Pluf_HTTP_Request $request
     * @return boolean the state of verification
     */
    public abstract function verify(Pluf_HTTP_Request $request): bool;

    /**
     * (non-PHPdoc)
     *
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        $coded = array(
            'type' => $this->getType(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'symbol' => $this->getSymbol()
        );
        return $coded;
    }

    /**
     * فهرستی از پارامترهای موتور پرداخت را تعیین می‌کند
     *
     * هر موتور پرداخت به دسته‌ای از پارامترها نیازمند است که باید توسط کاربر
     * تعیین شود. این فراخوانی پارامترهایی را تعیین می‌کند که برای استفاده از
     * این متور پرداخت باید تعیین کرد.
     *
     * خروجی این فراخوانی یک فهرست است توصیف خصوصیت‌ها است.
     */
    public function getParameters()
    {
        $param = array(
            'id' => $this->getType(),
            'name' => $this->getType(),
            'type' => 'struct',
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'editable' => true,
            'visible' => true,
            'priority' => 5,
            'symbol' => $this->getSymbol(),
            'children' => []
        );
        $general = $this->getGeneralParam();
        foreach ($general as $gp) {
            $param['children'][] = $gp;
        }

        $extra = $this->getExtraParam();
        foreach ($extra as $ep) {
            $param['children'][] = $ep;
        }
        return $param;
    }

    /**
     * فهرست خصوصیت‌های عمومی را تعیین می‌کند.
     *
     * @return
     *
     */
    public function getGeneralParam()
    {
        $params = array();
        // $params[] = array(
        // 'name' => 'title',
        // 'type' => 'String',
        // 'unit' => 'none',
        // 'title' => 'title',
        // 'description' => 'beackend title',
        // 'editable' => true,
        // 'visible' => true,
        // 'priority' => 5,
        // 'symbol' => 'title',
        // 'defaultValue' => 'no title',
        // 'validators' => [
        // 'NotNull',
        // 'NotEmpty'
        // ]
        // );
        // $params[] = array(
        // 'name' => 'description',
        // 'type' => 'String',
        // 'unit' => 'none',
        // 'title' => 'description',
        // 'description' => 'beackend description',
        // 'editable' => true,
        // 'visible' => true,
        // 'priority' => 5,
        // 'symbol' => 'title',
        // 'defaultValue' => 'description',
        // 'validators' => []
        // );
        return $params;
    }

    /**
     * خصوصیت‌های اضافه را تعیین می‌کند.
     */
    public function getExtraParam()
    {
        // TODO: maso, 1395: فرض شده که این فراخوانی توسط پیاده‌سازی‌ها بازنویسی
        // شود
        return array();
    }
}