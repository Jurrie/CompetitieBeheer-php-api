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

class Wedstrijd {

    public $accomodatie;
    public $afgelast;
    public $bijzonderheden;
    public $datum;
    public $id;
    public $kleedkamerThuis;
    public $kleedkamerUit;
    public $lastModifiedDate;
    public $lastModifiedUser;
    public $scheidsrechter;
    public $thuisTeam;
    public $uitslag;
    public $uitTeam;
    public $veld;
    public $verzameltijd;
    public $vrijveld1;
    public $vrijveld2;
    public $vrijveld3;

    public function __construct($accomodatie, $afgelast, $bijzonderheden, $datum, $id, $kleedkamerThuis, $kleedkamerUit, $lastModifiedDate, $lastModifiedUser, $scheidsrechter, $thuisTeam, $uitslag, $uitTeam, $veld, $verzameltijd, $vrijveld1, $vrijveld2, $vrijveld3) {
        $this->accomodatie = $accomodatie;
        $this->afgelast = $afgelast;
        $this->bijzonderheden = $bijzonderheden;
        $this->datum = $datum;
        $this->id = $id;
        $this->kleedkamerThuis = $kleedkamerThuis;
        $this->kleedkamerUit = $kleedkamerUit;
        $this->lastModifiedDate = $lastModifiedDate;
        $this->lastModifiedUser = $lastModifiedUser;
        $this->scheidsrechter = $scheidsrechter;
        $this->thuisTeam = $thuisTeam;
        $this->uitslag = $uitslag;
        $this->uitTeam = $uitTeam;
        $this->veld = $veld;
        $this->verzameltijd = $verzameltijd;
        $this->vrijveld1 = $vrijveld1;
        $this->vrijveld2 = $vrijveld2;
        $this->vrijveld3 = $vrijveld3;
    }

}

?>