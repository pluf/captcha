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
 * Sysetem captcha service
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *        
 */
class Captcha_Service
{

    /**
     * Find engine
     *
     * @param string $type
     * @return Captcha_Engine engine
     */
    public static function getEngine($type)
    {
        $items = self::engines();
        foreach ($items as $item) {
            if ($item->getType() === $type) {
                return $item;
            }
        }
        return null;
    }

    /**
     * Gets engines list
     *
     * @return array of engines
     */
    public static function engines()
    {
        return array(
            new Captcha_Engine_ReCAPTCHA()
        );
    }
}