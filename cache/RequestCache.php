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
require_once(dirname(__file__) . '/Cache.php');

/**
 * This is a Cache implementation that keeps the requested data in memory for the duration of this request.
 * In other words: all URLs will only be fetched once per HTTP request.
 */
class RequestCache extends Cache {

    private $cache = array(); // Key = url, value is retrieved content

    public function RequestCache() {
        parent::Cache();
    }

    public function retrieveUrl($url) {
        if (!isset($this->cache[$url])) {
            $result = file_get_contents($url);

            if ($result === FALSE) {
                throw new Exception('Cannot retrieve ' . $url);
            }

            $this->cache[$url] = $result;
            return $result;
        } else {
            return $this->cache[$url];
        }
    }

}

?>