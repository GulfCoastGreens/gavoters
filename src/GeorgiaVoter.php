<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CF\gavoters;

/**
 * Description of GeorgiaVoter
 *
 * @author james
 */
class GeorgiaVoter {
    public $CountyCode;
    public $RegistrationNbr;
    public $VoterStatus;
    public $VoterLastName;
    public $VoterFirstName;
    public $VoterMiddleMaiden;
    public $VoterNameSuffix;
    public $VoterNameTitle;
    public $ResHouseNbr;
    public $ResStreetName;
    public $ResStreetSuffix;
    public $ResAptUnitNbr;
    public $ResCityName;
    public $ResZip;
    public $ResZipPlus4;
    public $MidrFlag;
    public $PollWorker;
    public $TransactionCode;
    public $TransactionYearMonth;
    public $Filler1;
    public $DateOfBirth;
    public $RegistrationDate;
    public $Race;
    public $Gender;
    public $Absentee;
    public $LandDistrict;
    public $LandLot;
    public $OriginalRegDate;
    public $StatusReason;
    public $Filler2;
    public $CountyPrecinctId;
    public $CityPrecinctId;
    public $CongressionalDistrict;
    public $SenateDistrict;
    public $HouseDistrict;
    public $JudicialDistrict;
    public $CommDistrict;
    public $SchoolDistrict;
    public $CountyDistAName;
    public $CountyDistAValue;
    public $CountyDistBName;
    public $CountyDistBValue;
    public $MunicipalName;
    public $MunicipalCode;
    public $WardCityCouncilName;
    public $WardCityCouncil;
    public $CitySchoolDistName;
    public $CitySchoolDist;
    public $CityDistAName;
    public $CityDistAValue;
    public $CityDistBName;
    public $CityDistBValue;
    public $CityDistCName;
    public $CityDistCValue;
    public $CityDistDName;
    public $CityDistDValue;
    public $DateLastVotedDate;
    public $TypeOfElection;
    public $PartyLastVoted;
    public $LastContactDate;
    public $MailHouseNbr;
    public $MailStreetName;
    public $MailStreetSuffix;
    public $MailAptUnitNbr;
    public $MailCity;
    public $MailState;
    public $MailZip;
    public $MailZipPlus4;
    public $Filler3;
    public $MailAddress2;
    public $MailAddress3;
    public $MailCounty;
    public $DateAdded;
    public $DateChanged;
    public $DistrictCombo;
    public $ResBuilding;
    public $MailRRPOBox;
    public $CombinedStreetAddr;
    public $ExportDate;
    
    public static function arrayToInstance($voter) {
        $georgiaVoter = new self();
        foreach($voter as $key => $value){
            if(\property_exists($georgiaVoter, $key)) {
                if(\in_array($key, [
                    'VoterLastName',
                    'VoterFirstName',
                    'VoterMiddleMaiden',
                    'VoterNameSuffix',
                    'VoterNameTitle',
                    'ResStreetName',
                    'ResStreetSuffix',
                    'ResCityName',
                    'MailStreetName',
                    'MailStreetSuffix',
                    'MailCity',
                    'CombinedStreetAddr'])) {
                    $value = static::tidy($value);
                }
                $georgiaVoter->{$key} = $value;
            }
        }
        return $georgiaVoter;
    }
    public static function tidy($text) {
        return \implode(" ", \array_map(function($word) {
            $word = \ucfirst(\strtolower($word));
            //Specials like Mac, Mc etc
            $specials = ["Mac", "Mc", "O'"];
            foreach ($specials as $special) {
                $pos = \stripos($word, $special);
                if (($pos !== false) && ($pos == 0)) {
                    $parts = \explode($special, $word);
                    $word = $special . \ucfirst($parts[1]);
                }
            }
            //...but not for some words that begin with "Mac"
            // (make your own mind up about Macintosh, Maclure & Maclaren)
            $specials = ["macken", "macclesfield", "machynlleth"];
            if (\in_array(strtolower($word), $specials)) {
                $word = \ucfirst(\strtolower($word));
            }
            //Let"s go lower case on some words
            $specials = ["de", "la", "le", "on", "of", "and", "under", "upon"];
            if (\in_array(strtolower($word), $specials)) {
                $word = \strtolower($word);
            }
            return $word;
        }, \explode(" ", \preg_replace("/\s+/"," ",\trim($text)))));
    }

}
