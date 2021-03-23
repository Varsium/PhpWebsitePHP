<?php
//dynamische variabelen

$userid = $_SESSION["USER_ID"];
$naam = $_SESSION["USER_NAAM"];
//Gegevens ophalen van user
//---------------------------------------------------------------------------------------------
$sql = "SELECT * FROM werknemer WHERE WerknemerID = ?";
$stmt = $verbinding->prepare($sql);
$stmt->execute(array($userid));
$mezelf = $stmt->fetch(PDO::FETCH_ASSOC);

$mijnregister = $mezelf['rijksregisternummer'];
$mijnVoornaam = $mezelf['Voornaam'];
$mijnAchternaam = $mezelf['Naam'];
$mijnTelefoon = $mezelf['telefoon'];
$mijnEmail = $mezelf['email'];
$mijnGepensioneerd = $mezelf['gepensioneerd'];
$Bevestigd = $mezelf['Bevestigd'];
$RegisterPartner=$mezelf['PartnerRijksregisternummer'];
//---------------------------------------------------------------------------------------------
//Gegevens opvragen van Partner
$sql = "SELECT * FROM partner WHERE rijksregisternmr = (SELECT PartnerRijksregisternummer FROM werknemer where WerknemerID=?)";
$stmt = $verbinding->prepare($sql);
$stmt->execute(array($userid));
$partner = $stmt->fetch(PDO::FETCH_ASSOC);

$PartnerVoornaam = $partner['Voornaam'];
$PartnerAchternaam = $partner['naam'];
?>
