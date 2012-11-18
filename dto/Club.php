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

class Club {

    public $adres;
    public $cbtTenueUrl;
    public $emailAdresAlgemeen;
    public $emailAdresPenningmeester;
    public $id;
    public $logo;
    public $naam;
    public $plaats;
    public $postcode;
    public $telefoon;
    public $urlRoute;
    public $urlTenue;
    public $urlWebsite;

    public function __construct($adres, $cbtTenueUrl, $emailAdresAlgemeen, $emailAdresPenningmeester, $id, $logo, $naam, $plaats, $postcode, $telefoon, $urlRoute, $urlTenue, $urlWebsite) {
        $this->adres = $adres;
        $this->cbtTenueUrl = $cbtTenueUrl;
        $this->emailAdresAlgemeen = $emailAdresAlgemeen;
        $this->emailAdresPenningmeester = $emailAdresPenningmeester;
        $this->id = $id;
        $this->logo = $logo;
        $this->naam = $naam;
        $this->plaats = $plaats;
        $this->postcode = $postcode;
        $this->telefoon = $telefoon;
        $this->urlRoute = $urlRoute;
        $this->urlTenue = $urlTenue;
        $this->urlWebsite = $urlWebsite;
    }

}

?>