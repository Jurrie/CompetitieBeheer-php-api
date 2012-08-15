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

class Rank {

    public $creatorClubId;
    public $creatorHash;
    public $doelsaldo;
    public $dpt;
    public $dpv;
    public $gelijk;
    public $gespeeld;
    public $gewonnen;
    public $knvbNaam;
    public $plaats;
    public $punten;
    public $puntenMindering;
    public $teamId;
    public $verloren;

    public function __construct($creatorClubId, $creatorHash, $doelsaldo, $dpt, $dpv, $gelijk, $gespeeld, $gewonnen, $knvbNaam, $plaats, $punten, $puntenMindering, $teamId, $verloren) {
        $this->creatorClubId = $creatorClubId;
        $this->creatorHash = $creatorHash;
        $this->doelsaldo = $doelsaldo;
        $this->dpt = $dpt;
        $this->dpv = $dpv;
        $this->gelijk = $gelijk;
        $this->gespeeld = $gespeeld;
        $this->gewonnen = $gewonnen;
        $this->knvbNaam = $knvbNaam;
        $this->plaats = $plaats;
        $this->punten = $punten;
        $this->puntenMindering = $puntenMindering;
        $this->teamId = $teamId;
        $this->verloren = $verloren;
    }

}

?>