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
 * This is a Cache implementation that stores the responses on disk in a directory structure.
 */
class FileCache extends Cache {

    private $tmpDir;

    public function FileCache($tmpDir) {
        parent::Cache();
        $this->tmpDir = $tmpDir;
    }

    public function retrieveUrl($url) {
        $tmpFile = $this->initTmpDir($url) . "result.xml";
        if (file_exists($tmpFile) && filemtime($tmpFile) + $this->getTimeToCache() > time()) {
            // Returning cached data
            return file_get_contents($tmpFile);
        } else {
            // Cache is stale, so try to retrieve fresh data from web
            $data = @file_get_contents($url);
            if ($data !== false) {
                // Retrieved new data, cache it and return it
                file_put_contents($tmpFile, $data);
                return $data;
            } else {
                // We could not retrieve from web, so use stale cache anyway
                if (file_exists($tmpFile)) {
                    // Could not retrieve new data, returning stale
                    return file_get_contents($tmpFile);
                } else {
                    // Could not retrieve new data and don't have stale cached data, returning NULL
                    return NULL;
                }
            }
        }
    }

    private function initTmpDir($url) {
        $strTmpDir = $this->tmpDir . "/" . str_replace('http://feeds.competitiebeheer.nl/', '', $url) . "/";
        if (!file_exists($strTmpDir)) {
            mkdir($strTmpDir, 0700, TRUE);
        }
        return $strTmpDir;
    }

}

?>