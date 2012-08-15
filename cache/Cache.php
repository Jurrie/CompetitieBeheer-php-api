<?php

/*
 * Copyright 2012 Jurrie Overgoor <jurrie@narrowxpres.nl>
 * 
 * This file is part of CompetitieBeheer-php-api.
 * 
 * CompetitieBeheer-php-api is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the License,
 * or (at your option) any later version.
 * 
 * CompetitieBeheer-php-api is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser
 * General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with CompetitieBeheer-php-api. If not, see
 * <http://www.gnu.org/licenses/>.
 */

/**
 * A cache is an object that keeps track of requests made, and responses retrieved.
 * When the same request is made again within the 'cache treshold', the stored response is returned, and no call to the server is done.
 * This keeps the number of requests to the server to a minimum.
 */
abstract class Cache {

    private static $_instance = null;
    // Seconds to cache a previous result
    private $timeToCache = 86400; // 60 * 60 * 24

    public static function get() {
        if (self::$_instance === null) {
            throw new Exception("You should define a Cache explicitly");
        }

        return self::$_instance;
    }

    private $retriever;

    protected function Cache() {
        self::$_instance = $this;
    }

    public function getTimeToCache() {
        return $this->timeToCache;
    }

    public function setTimeToCache($timeToCache) {
        $this->timeToCache = $timeToCache;
    }

    public abstract function retrieveUrl($url);
}

?>