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
require_once(dirname(__file__) . '/Utils.php');

require_once(dirname(__file__) . '/dto/Wedstrijd.php');
require_once(dirname(__file__) . '/dto/Team.php');
require_once(dirname(__file__) . '/dto/Rank.php');
require_once(dirname(__file__) . '/dto/Speler.php');
require_once(dirname(__file__) . '/dto/Entiteit.php');
require_once(dirname(__file__) . '/dto/Competitie.php');

class CompetitieBeheer {

    const BASE_URL = 'http://feeds.competitiebeheer.nl/';

    /**
     * Holds the cache implementation we should use to cache the results of the requests.
     * @var Cache
     */
    private $cache;
    
    /**
     * Holds the club id of the account we should use to query CBT.
     * @var integer
     */
    private $clubId;
    
    /**
     * Holds the club hash of the account we should use to query CBT.
     * @var string
     */
    private $clubHash;

    /**
     * @param Cache $cache The cache variant we want to use for caching results
     * @param integer $clubId The id of your club (can be obtained with the CompetitieBeheer tool by going to “mijn club”)
     * @param string $clubHash The hash of your club (can be obtained with the CompetitieBeheer tool by going to “mijn club”)
     */
    public function __construct($cache, $clubId, $clubHash) {
        $this->cache = $cache;
        $this->clubId = $clubId;
        $this->clubHash = $clubHash;
    }

    public function getCache() {
        return $this->cache;
    }

    /**
     * Returns all matches, filtered on a number of input parameters.
     * 
     * Either parameter $teamId or $competitieId is mandatory.
     * 
     * @param integer $teamId Het id van het team waar je wedstrijden van wilt opvragen. Hiermee kun je dus specifiek voor 1 team wedstrijden opvragen. Dit kan gevonden worden bij “mijn teams”. Let op dat je via de column-chooser de ID-column even actief moet maken (http://www.competitiebeheer.nl/index.php/videos/104-kolommen-beheren-en-exporteren)
     * @param integer $competitieId Het id van de competitie. Hiermee kun je van een competitie alle wedstrijden opvragen dus ook wedstrijden van andere teams in desbetreffende competitie. Dit id kan gevonden worden bij “mijn competities”. Ook hiervoor geldt dat dit via de column-chooser gekozen moet worden.
     * @param boolean $afgelast Hiermee geef je aan of je afgelaste wedstrijden wilt zien of juist niet afgelaste wedstrijden. Als je deze parameter niet invult krijg je afgelaste wedstrijden en niet afgelaste wedstrijden door elkaar. (parameter is optioneel)
     * @param date $datumVanaf Kan worden gebruikt om wedstrijden op te halen in een specifieke datum-range. (parameter is optioneel)
     * @param date $datumTot Kan worden gebruikt om wedstrijden op te halen in een specifieke datum-range. (parameter is optioneel)
     * @param boolean $bevatOfficieleUitslag Hiermee geef je aan of je alleen wedstrijden wilt terugkrijgen die een officiele uitslag hebben. Officiele uitslagen zijn uitslagen die via voetbal.nl zijn binnengekomen. Hiermee kun je dus feitelijk een uitslagen overzicht maken. (parameter is optioneel)
     * @param boolean $bevatUitslagen Hiermee geef je aan of je alleen wedstrijden wilt terugkrijgen die een on-officiele uitslagen hebben. On-officele uitslagen zijn uitslagen die bijvoorbeeld via twitter zijn binnengekomen. Deze gebruik ik bijvoorbeeld om realtime-virtuele-standen te genereren. (parameter is optioneel)
     * @return Match[] array of Match instances 
     */
    public function getMatches($teamId = null, $competitieId = null, $afgelast = null, $datumVanaf = null, $datumTot = null, $bevatOfficieleUitslag = null, $bevatUitslagen = null) {
        // http://feeds.competitiebeheer.nl/wedstrijden.svc/?clubid={clubid}&clubhash={clubhash}&teamid={teamid}

        if ($teamId === null && $competitieId === null) {
            throw new Exception("Either teamId or competitieId should be given");
        }

        $url = self::BASE_URL . "/Wedstrijden.svc/?clubid=$this->clubId&clubhash=$this->clubHash";
        if ($teamId !== null)
            $url .= "&teamid=$teamId";
        if ($competitieId !== null)
            $url .= "&competitieid=$competitieId";
        if ($afgelast !== null)
            $url .= "&afgelast=$afgelast";
        if ($datumVanaf !== null)
            $url .= "&datumvanaf=".date('d-m-Y', $datumVanaf);
        if ($datumTot !== null)
            $url .= "&datumtot=".date('d-m-Y', $datumTot);
        if ($bevatOfficieleUitslag !== null)
            $url .= "&bevatofficieleuitslag=$bevatOfficieleUitslag";
        if ($bevatUitslagen !== null)
            $url .= "&bevatuitslagen=$bevatUitslagen";

        $xml = $this->cache->retrieveUrl($url);
        $xml = new SimpleXMLElement($xml);

        $result = array();
        foreach ($xml->entry as $xmlEntry) {
            $match = $this->parseMatch($xmlEntry->content->Wedstrijd);
            $result[] = $match;
        }
        return $result;
    }

    public function getRanking($competitionId) {
        // http://feeds.competitiebeheer.nl/Standen.svc/?clubid={clubid}&clubhash={clubhash}&competitieid={teamid}
        $url = self::BASE_URL . "/Standen.svc/?clubid=$this->clubId&clubhash=$this->clubHash&competitieid=$competitionId";
        $xml = $this->cache->retrieveUrl($url);
        $xml = new SimpleXMLElement($xml);

        $result = array();
        foreach ($xml->entry as $xmlEntry) {
            $match = $this->parseRank($xmlEntry->content->Stand);
            $result[] = $match;
        }
        return $result;
    }
    
    /**
     * Returns all the teams for this club id.
     * @param boolean $archived if TRUE, we also return archived entries
     * @return Team[] array of Team objects 
     */
    public function getTeams($archived = false) {
        // http://feeds.competitiebeheer.nl/Teams.svc/?clubid={clubid}&clubhash={clubhash}&archived={true/false}
        // Parameter archived is mandatory
        $url = self::BASE_URL . "/Teams.svc/?clubid=".$this->clubId."&clubhash=".$this->clubHash;
        if ($archived === true)
            $url .= "&archived=true";
        else
            $url .= "&archived=false";

        $xml = $this->cache->retrieveUrl($url);
        $xml = new SimpleXMLElement($xml);

        $result = array();
        foreach ($xml->entry as $xmlEntry) {
            $match = $this->parseTeam($xmlEntry->content->Team);
            $result[] = $match;
        }
        return $result;
    }

    /**
     * Returns a specific team for this club id.
     * @param integer $teamId the id of the team to retrieve
     * @param boolean $archived if TRUE, we also return archived entries
     * @return Team Team object we retrieved, or NULL if the team is not found
     */
    public function getTeam($teamId, $archived = false) {
        // http://feeds.competitiebeheer.nl/Teams.svc/Team/{teamid}?clubid={clubid}&clubhash={clubhash}&archived={true/false}
        $url = self::BASE_URL . "/Teams.svc/Team/".$teamId."?clubid=".$this->clubId."&clubhash=".$this->clubHash;
        if ($archived === true)
            $url .= "&archived=true";
        else
            $url .= "&archived=false";

        $xml = $this->cache->retrieveUrl($url);
        if ($xml === null)
        {
            // Team not found
            return null;
        }
        $xml = new SimpleXMLElement($xml);

        $match = $this->parseTeam($xml->content->Team);
        return $match;
    }
    
    public function getPlayers($teamId) {
        // http://feeds.competitiebeheer.nl/Spelers.svc/?clubid={clubid}&clubhash={clubhash}&teamId={teamid}
        $url = self::BASE_URL . "/Spelers.svc/?clubid=$this->clubId&clubhash=$this->clubHash&teamid=$teamId";
        $xml = $this->cache->retrieveUrl($url);
        $xml = new SimpleXMLElement($xml);

        $result = array();
        foreach ($xml->entry as $xmlEntry) {
            $match = $this->parsePlayer($xmlEntry->content->Speler);
            $result[] = $match;
        }
        return $result;
    }

    public function getPlayer($playerId) {
        // http://feeds.competitiebeheer.nl/Spelers.svc/Speler/{playerid}?clubid={clubid}&clubhash={clubhash}
        $url = self::BASE_URL . "/Spelers.svc/Speler/".$playerId."?clubid=$this->clubId&clubhash=$this->clubHash";
        $xml = $this->cache->retrieveUrl($url);
        if ($xml === null)
        {
            // Player not found
            return null;
        }
        $xml = new SimpleXMLElement($xml);

        $player = $this->parsePlayer($xml->content->Speler);
        return $player;
    }
    
    public function getCompetitions($seasonStartYear) {
        // http://feeds.competitiebeheer.nl/Competities.svc/?clubid={clubid}&clubhash={clubhash}&seizoenstartjaar={year}
        $url = self::BASE_URL . "/Competities.svc/?clubid=$this->clubId&clubhash=$this->clubHash&seizoenstartjaar=$seasonStartYear";
        $xml = $this->cache->retrieveUrl($url);
        $xml = new SimpleXMLElement($xml);

        $result = array();
        foreach ($xml->entry as $xmlEntry) {
            $competition = $this->parseCompetition($xmlEntry->content->Competitie);
            $result[] = $competition;
        }
        return $result;
    }
    
    public function getCompetition($competitionId) {
        // http://feeds.competitiebeheer.nl/Competities.svc/Speler/{competitionid}?clubid={clubid}&clubhash={clubhash}
        $url = self::BASE_URL . "/Competities.svc/Speler/".$competitionId."?clubid=$this->clubId&clubhash=$this->clubHash";
        $xml = $this->cache->retrieveUrl($url);
        if ($xml === null)
        {
            // Competition not found
            return null;
        }
        $xml = new SimpleXMLElement($xml);

        $competition = $this->parseCompetition($xml->content->Competitie);
        return $competition;
    }

    private function parseMatch($xmlMatch) {
        $accomodatie = (string) $xmlMatch->ACCOMMODATIE;
        $afgelast = Utils::string2Boolean($xmlMatch->AFGELAST);
        $bijzonderheden = (string) $xmlMatch->BIJZONDERHEDEN;
        $datum = Utils::ISO8601Date2UnixTimestamp($xmlMatch->DATUM);
        $id = (string) $xmlMatch->ID;
        $kleedkamer = (string) $xmlMatch->KLEEDKAMER;
        $lastModifiedDate = Utils::ISO8601Date2UnixTimestamp($xmlMatch->LASTMODIFIEDDATE);
        $lastModifiedUser = (string) $xmlMatch->LASTMODIFIEDUSER_USERNAME;
        $scheidsrechter = (string) $xmlMatch->SCHEIDSRECHTER;
        $thuisTeam = $this->parseTeam($xmlMatch->THUIS_TEAM);
        $uitslag = (string) $xmlMatch->UITSLAG;
        $uitTeam = $this->parseTeam($xmlMatch->UIT_TEAM);
        $veld = (string) $xmlMatch->VELD;
        $verzameltijd = (string) $xmlMatch->VERZAMELTIJD;
        $vrijveld1 = (string) $xmlMatch->VRIJVELD1;
        $vrijveld2 = (string) $xmlMatch->VRIJVELD2;
        $vrijveld3 = (string) $xmlMatch->VRIJVELD3;
        $wedstrijdType = (string) $xmlMatch->WEDSTRIJDTYPEENUM;
        return new Wedstrijd($accomodatie, $afgelast, $bijzonderheden, $datum, $id, $kleedkamer, $lastModifiedDate, $lastModifiedUser, $scheidsrechter, $thuisTeam, $uitslag, $uitTeam, $veld, $verzameltijd, $vrijveld1, $vrijveld2, $vrijveld3, $wedstrijdType);
    }

    private function parseTeam($xmlTeam) {
        $archief = Utils::string2Boolean($xmlTeam->ARCHIEF);
        $competitieId = (string) $xmlTeam->COMPETITIE_ID;
        $id = (string) $xmlTeam->ID;
        $knvbNaam = (string) $xmlTeam->KNVB_NAAM;
        $naam = (string) $xmlTeam->NAAM;
        $puntenmindering = (string) $xmlTeam->PUNTENMINDERING;
        $seizoenBeginJaar = (string) $xmlTeam->SEIZOEN_BEGIN_JAAR;
        $seizoenEindJaar = (string) $xmlTeam->SEIZOEN_EIND_JAAR;
        $teamFoto = (string) $xmlTeam->TEAM_FOTO;
        $voorkeursTijd = (string) $xmlTeam->VOORKEURSTIJD;
        return new Team($archief, $competitieId, $id, $knvbNaam, $naam, $puntenmindering, $seizoenBeginJaar, $seizoenEindJaar, $teamFoto, $voorkeursTijd);
    }

    private function parseRank($xmlRank) {
        $creatorClubId = (string) $xmlRank->CREATORCLUBID;
        $creatorHash = (string) $xmlRank->CREATORHASH;
        $doelsaldo = (string) $xmlRank->DOELSALDO;
        $dpt = (string) $xmlRank->DPT;
        $dpv = (string) $xmlRank->DPV;
        $gelijk = (string) $xmlRank->GELIJK;
        $gespeeld = (string) $xmlRank->GESPEELD;
        $gewonnen = (string) $xmlRank->GEWONNEN;
        $knvbNaam = (string) $xmlRank->KNVB_NAAM;
        $plaats = (string) $xmlRank->PLAATS;
        $punten = (string) $xmlRank->PUNTEN;
        $puntenMindering = (string) $xmlRank->PUNTEN_MINDERING;
        $teamId = (string) $xmlRank->TEAM_ID;
        $verloren = (string) $xmlRank->VERLOREN;
        return new Rank($creatorClubId, $creatorHash, $doelsaldo, $dpt, $dpv, $gelijk, $gespeeld, $gewonnen, $knvbNaam, $plaats, $punten, $puntenMindering, $teamId, $verloren);
    }
    
    private function parsePlayer($xmlPlayer) {
        $entiteit = $this->parseEntity($xmlPlayer->ENTITEIT);
        $fotoUrl = (string)$xmlPlayer->FOTOURL;
        $id = (string)$xmlPlayer->ID;
        $naam = (string)$xmlPlayer->NAAM;
        $rugnummer = (string)$xmlPlayer->RUGNR;
        return new Speler($entiteit, $fotoUrl, $id, $naam, $rugnummer);
    }
    
    private function parseEntity($xmlEntity) {
        $achternaam = (string) $xmlEntity->ACHTERNAAM;
        $adres = (string) $xmlEntity->ADRES;
        $email = (string) $xmlEntity->EMAIL;
        $geboortedatum = Utils::ISO8601Date2UnixTimestamp($xmlEntity->GEBOORTEDATUM);
        $mobiel = (string)$xmlEntity->MOBIEL;
        $plaats = (string)$xmlEntity->PLAATS;
        $postcode = (string)$xmlEntity->POSTCODE;
        $voornaam = (string)$xmlEntity->VOORNAAM;
        return new Entiteit($achternaam, $adres, $email, $geboortedatum, $mobiel, $plaats, $postcode, $voornaam);
    }
    
    private function parseCompetition($xmlEntity) {
        $amatweetId = (string) $xmlEntity->AMATWEET_ID;
        $competitieType = (string) $xmlEntity->COMPETITIETYPE;
        $competitieAanduidingId = (string) $xmlEntity->COMPETITIE_AANDUIDING_ID;
        $competitieDistrictId = (string) $xmlEntity->COMPETITIE_DISTRICT_ID;
        $competitieKlasseId = (string) $xmlEntity->COMPETITIE_KLASSE_ID;
        $competitiePouleId = (string) $xmlEntity->COMPETITIE_POULE_ID;
        $createdDate = Utils::ISO8601Date2UnixTimestamp($xmlEntity->CREATEDDATE);
        $handmatig = Utils::string2Boolean($xmlMatch->HANDMATIG);
        $id = (string) $xmlEntity->ID;
        $knvbId = (string) $xmlEntity->KNVB_ID;
        $knvbNaam = (string) $xmlEntity->KNVB_NAAM;
        $lastModifiedUserId = (string) $xmlEntity->LASTMODIFIEDUSER_ID;
        $naam = (string) $xmlEntity->NAAM;
        $scoreflashId = (string) $xmlEntity->SCOREFLASH_ID;
        $seizoenBeginJaar = (string) $xmlEntity->SEIZOEN_BEGIN_JAAR;
        $seizoenEindJaar = (string) $xmlEntity->SEIZOEN_EIND_JAAR;
        return new Competitie($amatweetId, $competitieType, $competitieAanduidingId, $competitieDistrictId, $competitieKlasseId, $competitiePouleId, $createdDate, $handmatig, $id, $knvbId, $knvbNaam, $lastModifiedUserId, $naam, $scoreflashId, $seizoenBeginJaar, $seizoenEindJaar);
    }

}

?>