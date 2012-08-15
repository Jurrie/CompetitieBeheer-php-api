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

class Team {

    public $archief;
    public $competitieId;
    public $id;
    public $knvbNaam;
    public $naam;
    public $puntenmindering;
    public $seizoenBeginJaar;
    public $seizoenEindJaar;
    public $teamFoto;
    public $voorkeursTijd;

    public function __construct($archief, $competitieId, $id, $knvbNaam, $naam, $puntenmindering, $seizoenBeginJaar, $seizoenEindJaar, $teamFoto, $voorkeursTijd) {
        $this->archief = $archief;
        $this->competitieId = $competitieId;
        $this->id = $id;
        $this->knvbNaam = $knvbNaam;
        $this->naam = $naam;
        $this->puntenmindering = $puntenmindering;
        $this->seizoenBeginJaar = $seizoenBeginJaar;
        $this->seizoenEindJaar = $seizoenEindJaar;
        $this->teamFoto = $teamFoto;
        $this->voorkeursTijd = $voorkeursTijd;
    }

}

?>