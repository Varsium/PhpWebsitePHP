<?php
include("Prijzen.php");

$sql = "SELECT * FROM werknemer WHERE WerknemerID = ?";
$stmt = $verbinding->prepare($sql);
$stmt->execute(array($userid));
$mezelf = $stmt->fetch(PDO::FETCH_ASSOC);

//Alle gegevens van werknemer
$mijnregister = $mezelf['rijksregisternummer'];
$mijnVoornaam = $mezelf['Voornaam'];
$mijnAchternaam = $mezelf['Naam'];
$mijnTelefoon = $mezelf['telefoon'];
$mijnEmail = $mezelf['email'];
$mijnGepensioneerd = $mezelf['gepensioneerd'];
$Bevestigd = $mezelf['Bevestigd'];
$partnerid = $mezelf["Partner_ID"];

if ($mijnGepensioneerd == 0) {
    $mijnGepensioneerd = "Niet gepensioneerd";
} elseif ($mijnGepensioneerd == 1) {
    $mijnGepensioneerd = "U bent gepensioneerd";
}


$sql = "SELECT omschrijving FROM activiteit WHERE Activiteit_ID =(select Activiteit_ID from deelnemer where rijksregisternummer=?) ";
$stmt = $verbinding->prepare($sql);
$stmt->execute(array($mijnregister));
$mijnActiviteit = $stmt->fetch(PDO::FETCH_ASSOC);
//activiteit van werknemer
$mijnKeuzeActiviteit = $mijnActiviteit["omschrijving"];


$sql = "SELECT * FROM gerechten WHERE Type_ID =(select Type_ID from menu where rijksregisternummer=?) ";
$stmt = $verbinding->prepare($sql);
$stmt->execute(array($mijnregister));
$mijnGerechten = $stmt->fetch(PDO::FETCH_ASSOC);
//Gerechten werkenmer
$mijnVoorgerecht = $mijnGerechten["Voorgerecht"];
$mijnSoep = $mijnGerechten["Soep"];
$mijnVisgerecht = $mijnGerechten["Visgerecht"];
$mijnVleesgerecht = $mijnGerechten["Vleesgerecht"];
$mijnDessert = $mijnGerechten["Dessert"];


//---------------------------------------------------------------------------------------------------------------------
//alles voor Partner:
$sql = "SELECT * FROM partner WHERE Partner_ID =?";
$stmt = $verbinding->prepare($sql);
$stmt->execute(array($partnerid));
$partner = $stmt->fetch(PDO::FETCH_ASSOC);
//gegevens Partner
$Partnerregister = $partner['rijksregisternmr'];
$PartnerVoornaam = $partner['Voornaam'];
$PartnerAchternaam = $partner['naam'];

$sql = "SELECT omschrijving FROM activiteit WHERE Activiteit_ID =(select Activiteit_ID from deelnemer where rijksregisternummer=?) ";
$stmt = $verbinding->prepare($sql);
$stmt->execute(array($Partnerregister));
$partneractiviteit = $stmt->fetch(PDO::FETCH_ASSOC);
$partnerkeuzeactiviteit = $partneractiviteit["omschrijving"];

$sql = "SELECT * FROM gerechten WHERE Type_ID =(select Type_ID from menu where rijksregisternummer=?) ";
$stmt = $verbinding->prepare($sql);
$stmt->execute(array($Partnerregister));
$PartnerGerechten = $stmt->fetch(PDO::FETCH_ASSOC);
//Gerechten Partner
$partnerVoorgerecht = $PartnerGerechten["Voorgerecht"];
$partnerSoep = $PartnerGerechten["Soep"];
$partnerVisgerecht = $PartnerGerechten["Visgerecht"];
$partnerVleesgerecht = $PartnerGerechten["Vleesgerecht"];
$partnerDessert = $PartnerGerechten["Dessert"];
//---------------------------------------------------------------------------------------------------------------------------------------------------------------

//Gestructuurde mededeling maken:
$controlegetal=$mijnregister%997;
$GestructureerdeMededeling= "+++".substr($mijnregister,0,5)."/".substr($mijnregister,5,4)."/".$controlegetal."+++";


//voorwaarden:
$gangen="je hebt gekozen voor een 3gangen menu dit kost u ".$maltijdprijs3G." euro";
$gangenP="je hebt gekozen voor een 3gangen menu dit kost u".$maltijdprijsP3G." euro";
if ($mijnVisgerecht == "Gebakken kreeft") {
    $mijnVisgerecht = $mijnVisgerecht . " Dit suplement kost u 20 euro ";
    $totaalprijs += $kreeftprijs;
}

if (($mijnGepensioneerd == 1) && ($mijnSoep == "")) {
    $totaalprijs += $maltijdprijspensioen3G;
    $gangen="je hebt gekozen voor een 3gangen menu dit kost u ".$maltijdprijspensioen3G." euro";
} elseif (($mijnGepensioneerd == 1) && ($mijnSoep != "")) {
    $totaalprijs += $maltijdprijspensioen4G;
    $gangen="je hebt gekozen voor een 4gangen menu dit kost u ".$maltijdprijspensioen4G." euro";
}
if ($mijnSoep == "") {
    $mijnSoep = "Soep: uw heeft geen soep gekozen";
    $totaalprijs+=$maltijdprijs3G;
} else if ($mijnSoep != "") {
    $mijnSoep = "Soep:" . $mijnSoep;
    $totaalprijs+=$maltijdprijs4G;
    $gangen="je hebt gekozen voor een 4gangen menu dit kost u ".$maltijdprijs4G." euro";
}
if ($partnerkeuzeactiviteit != "Geen activiteit") {
    $partnerkeuzeactiviteit="Uw activiteit is: ".$partnerkeuzeactiviteit." dit kost u ".$activiteitprijs." euro extra";
    $totaalprijsP += $activiteitprijs;
}
elseif ($partnerkeuzeactiviteit == "Geen activiteit"){
    $partnerkeuzeactiviteit="uw activiteit is: ".$partnerkeuzeactiviteit;
}
if ($partnerSoep == "") {
    $totaalprijsP += $maltijdprijsP3G;
    $partnerSoep="Soep: u heeft geen soep gekozen";
} else if ($partnerSoep != "") {
    $totaalprijsP += $maltijdprijsP4G;
    $gangenP="je hebt gekozen voor een 4gangen menu dit kost u".$maltijdprijsP4G." euro";
    $partnerSoep="Soep: ".$partnerSoep;

}
if ($partnerVisgerecht == "Gebakken kreeft") {
    $totaalprijsP += $kreeftprijs;
    $partnerVisgerecht=$partnerVisgerecht." dit kost u 20 euro extra";
}


if ($mezelf["Partner_ID"] == null) {
    $bericht = "beste $mijnVoornaam,<br>
                uw gegevens zijn:<br>
                Voornaam: $mijnVoornaam<br>
                Achternaam: $mijnAchternaam<br>
                Rijksregisternummer: $mijnregister<br>
                Telefoon: $mijnTelefoon<br>
                Email: $mijnEmail<br>
                Pensioen: $mijnGepensioneerd<br>
                <br>
                Uw activiteit is : $mijnKeuzeActiviteit<br>
                <br>
                Uw bestelde menu is :$gangen<br>
                Voorgerecht: $mijnVoorgerecht<br>
                $mijnSoep<br>
                Visgerecht: $mijnVisgerecht<br>
                Vleesgerecht:$mijnVleesgerecht<br>
                Dessert: $mijnDessert<br>
                <br>
                het kost u in totaal : $totaalprijs<br>
                <br>
                Gelieve te betalen 2 weken na ontvangst van deze mail met de volgende mededeling :<br>
                $GestructureerdeMededeling";

} else {
    $samengeteldeprijs = $totaalprijs + $totaalprijsP;
    $bericht = "beste $mijnVoornaam,<br>
                uw gegevens zijn:<br>
                Voornaam: $mijnVoornaam<br>
                Achternaam: $mijnAchternaam<br>
                Rijksregisternummer: $mijnregister<br>
                Telefoon: $mijnTelefoon<br>
                Email: $mijnEmail<br>
                Pensioen: $mijnGepensioneerd<br>
                <br>
                Uw activiteit is : $mijnKeuzeActiviteit<br>
                <br>
                Uw bestelde menu is : $gangen<br>
                Voorgerecht: $mijnVoorgerecht<br>
                $mijnSoep<br>
                Visgerecht: $mijnVisgerecht<br>
                Vleesgerecht:$mijnVleesgerecht<br>
                Dessert: $mijnDessert<br>
                <br>
                het kost u : $totaalprijs euro<br>
                <br>
                Voor de partner:<br>
                beste $PartnerVoornaam,<br>
                uw gegevens zijn:<br>
                Voornaam: $PartnerVoornaam<br>
                Achternaam: $PartnerAchternaam<br>
                Rijksregisternummer: $Partnerregister<br>
                <br>
                $partnerkeuzeactiviteit<br>
                <br>
                Uw bestelde menu is: $gangenP<br>
                Voorgerecht: $partnerVoorgerecht<br>
                $partnerSoep<br>
                Visgerecht: $partnerVisgerecht<br>
                Vleesgerecht:$partnerVleesgerecht<br>
                Dessert: $partnerDessert<br>
                Het kost u partner : $totaalprijsP euro<br>
                <br>
                in het totaal kost dit alles :$samengeteldeprijs euro<br>
                <br>
                 Gelieve te betalen 2 weken na ontvangst van deze mail met de volgende mededeling :<br>
                $GestructureerdeMededeling";}
mailen($mijnEmail,$mijnVoornaam,"bevestiging gegevens",$bericht);
?>
                
