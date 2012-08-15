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

class Entiteit {

    public $achternaam;
    public $adres;
    public $email;
    public $geboortedatum;
    public $mobiel;
    public $plaats;
    public $postcode;
    public $voornaam;

    public function __construct($achternaam, $adres, $email, $geboortedatum, $mobiel, $plaats, $postcode, $voornaam) {
        $this->achternaam = $achternaam;
        $this->adres = $adres;
        $this->email = $email;
        $this->geboortedatum = $geboortedatum;
        $this->mobiel = $mobiel;
        $this->plaats = $plaats;
        $this->postcode = $postcode;
        $this->voornaam = $voornaam;
    }

}

?>