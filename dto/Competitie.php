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

class Competitie {

    public $amatweetId;
    public $competitieType;
    public $competitieAanduidingId;
    public $competitieDistrictId;
    public $competitieKlasseId;
    public $competitiePouleId;
    public $createdDate;
    public $handmatig;
    public $id;
    public $knvbId;
    public $knvbNaam;
    public $lastModifiedUserId;
    public $naam;
    public $scoreflashId;
    public $seizoenBeginJaar;
    public $seizoenEindJaar;

    public function __construct($amatweetId, $competitieType, $competitieAanduidingId, $competitieDistrictId, $competitieKlasseId, $competitiePouleId, $createdDate, $handmatig, $id, $knvbId, $knvbNaam, $lastModifiedUserId, $naam, $scoreflashId, $seizoenBeginJaar, $seizoenEindJaar) {
        $this->amatweetId = $amatweetId;
        $this->competitieType = $competitieType;
        $this->competitieAanduidingId = $competitieAanduidingId;
        $this->competitieDistrictId = $competitieDistrictId;
        $this->competitieKlasseId = $competitieKlasseId;
        $this->competitiePouleId = $competitiePouleId;
        $this->createdDate = $createdDate;
        $this->handmatig = $handmatig;
        $this->id = $id;
        $this->knvbId = $knvbId;
        $this->knvbNaam = $knvbNaam;
        $this->lastModifiedUserId = $lastModifiedUserId;
        $this->naam = $naam;
        $this->scoreflashId = $scoreflashId;
        $this->seizoenBeginJaar = $seizoenBeginJaar;
        $this->seizoenEindJaar = $seizoenEindJaar;
    }

}

?>