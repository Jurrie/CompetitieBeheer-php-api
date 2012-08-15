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

class Speler {

    public $entiteit;
    public $fotoUrl;
    public $id;
    public $naam;
    public $rugnummer;

    public function __construct($entiteit, $fotoUrl, $id, $naam, $rugnummer) {
        $this->entiteit = $entiteit;
        $this->fotoUrl = $fotoUrl;
        $this->id = $id;
        $this->naam = $naam;
        $this->rugnummer = $rugnummer;
    }

}

?>