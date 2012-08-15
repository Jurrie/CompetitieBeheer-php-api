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
require_once('Cache.php');

/**
 * This is a Cache implementation that stores the responses in a MySQL database table.
 */
class MySQLCache extends Cache {

    private $table;
    private $dbConnection;

    public function MySQLCache($server, $username, $password, $database, $table) {
        $this->table = $table;

        // Connect to the database
        $this->dbConnection = mysql_connect($server, $username, $password);
        if ($this->dbConnection === FALSE) {
            throw new Exception("Cannot connect to MySQL database: " . mysql_error(), mysql_errno());
        }
        if (mysql_select_db($database, $this->dbConnection) === FALSE) {
            throw new Exception("Cannot select database: " . mysql_error($this->dbConnection), mysql_errno($this->dbConnection));
        }

        // Check if our cache table exists
        $result = mysql_query("SHOW TABLES LIKE '" . mysql_real_escape_string($table) . "'", $this->dbConnection);
        if ($result === FALSE) {
            throw new Exception("Cannot query for existance of table: " . mysql_error($this->dbConnection), mysql_errno($this->dbConnection));
        }
        if (mysql_num_rows($result) !== 1) {
            self::createCacheTable($this->dbConnection, $table);
        }

        parent::Cache();
    }

    public static function createCacheTable($link, $table) {
        $sql = "CREATE TABLE `" . mysql_real_escape_string($table) . "` (";
        $sql .= "  `request` varchar(255) COLLATE utf8_bin NOT NULL,";
        $sql .= "  `result` longtext COLLATE utf8_bin NOT NULL,";
        $sql .= "  `timestamp` int(11) NOT NULL,";
        $sql .= "PRIMARY KEY (`request`)";
        $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";

        $result = mysql_query($sql, $link);
        if ($result === FALSE) {
            throw new Exception("Could not create cache table: " . mysql_error($link), mysql_errno($link));
        }
    }

    private function tryFromCache($timeToCache, $url) {
        $request = str_replace('http://feeds.competitiebeheer.nl/', '', $url);
        //$result = mysql_query("SELECT `result` FROM `".mysql_real_escape_string($this->table)."` WHERE `request` = '".mysql_real_escape_string($request)."' AND `timestamp` > ".(time()-$timeToCache), $this->dbConnection);
        $result = mysql_query("SELECT `result`, `timestamp` > " . (time() - $timeToCache) . " FROM `" . mysql_real_escape_string($this->table) . "` WHERE `request` = '" . mysql_real_escape_string($request) . "'", $this->dbConnection);

        if ($result === FALSE) {
            return NULL;
        }
        if (mysql_num_rows($result) !== 1) {
            return NULL;
        }
        return array(mysql_result($result, 0, 0), mysql_result($result, 0, 1));
    }

    private function putInCache($data, $url) {
        $request = str_replace('http://feeds.competitiebeheer.nl/', '', $url);
        $result = mysql_query("UPDATE `" . mysql_real_escape_string($this->table) . "` SET `result` = '" . mysql_real_escape_string($data) . "', `timestamp` = " . time() . " WHERE `request` = '" . mysql_real_escape_string($request) . "'", $this->dbConnection);
        if (mysql_affected_rows($this->dbConnection) < 1) {
            mysql_query("INSERT INTO `" . mysql_real_escape_string($this->table) . "` (`request`, `result`, `timestamp`) VALUES ('" . mysql_real_escape_string($request) . "', '" . mysql_real_escape_string($data) . "', " . time() . ")", $this->dbConnection);
        }
    }

    public function retrieveUrl($url) {
        $cacheData = $this->tryFromCache($this->getTimeToCache(), $url);
        if ($cacheData === NULL || $cacheData[1] !== '1') {
            // Cached data was stale, retrieve new from web
            $newData = @file_get_contents($url);
            if ($newData !== false) {
                // Retrieved new data, cache it and return it
                $this->putInCache($newData, $url);
                return $newData;
            } else {
                // Could not retrieve from web, use stale data anyway
                if ($cacheData !== NULL) {
                    // Could not retrieve new data, returning stale
                    return $cacheData[0];
                } else {
                    // Could not retrieve new data and don't have stale cached data, returning NULL
                    return NULL;
                }
            }
        } else {
            // Data from database was not stale, returning cached data
            return $cacheData[0];
        }
    }

}

?>