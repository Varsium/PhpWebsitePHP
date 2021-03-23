<?php
//Partner : activiteit :
$activiteitP = htmlspecialchars($_POST['activiteit2']);

//Gegevens Partner:
$voornaamP = htmlspecialchars($_POST['voornaam2']);
$achternaamP = htmlspecialchars($_POST['naam2']);
$registerP = htmlspecialchars($_POST['Register2']);
$unieknaamP = $voornaamP . $registerP; //Deze maak ik aan om unieke naam te creeren voor partner


//Menu Partner:
$gangenP = htmlspecialchars($_POST['gangenmenuP']);
$voorgerechtP = htmlspecialchars($_POST['voorgerecht2']);
$soepP = htmlspecialchars($_POST['soepen2']);
$visP = htmlspecialchars($_POST['visgerechten2']);
$vleesP = htmlspecialchars($_POST['vleesgerechten2']);
$dessertP = htmlspecialchars($_POST['desserts2']);
//Alles voor invoeren partner
//Invoeren alle gegevens Partner :
$TeControlerenGetal = substr($registerP, 0, 9);
$Controlegetal = substr($registerP, 9, 2);
$ModuloControle = ($TeControlerenGetal % 97);

if ($Controlegetal == (97 - $ModuloControle)) {
    $sql = "INSERT INTO partner (rijksregisternmr,Voornaam,naam) values ('$registerP','$voornaamP','$achternaamP')";
    $stmt = $verbinding->exec($sql);

//Heb een colom toegevoegd zodat ik later de gebruiker kan laten maken tussen zijn of zijn partner rijksregisternummer hiervoor heb ik ze gelinkt met Partner_ID)
    $partnerid = "SELECT Partner_ID FROM Partner where rijksregisternmr=?";
    $stmt = $verbinding->prepare($partnerid);
    $stmt->execute([$registerP]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    foreach ($result as $idpartner) {
    };
//einde voorbereiding

    $sql = "UPDATE Werknemer SET Partner_ID='$idpartner' where rijksregisternummer =?";
    $stmt = $verbinding->prepare($sql);
    $stmt->execute(array($mijnregister));

//Invoeren activiteit keuze Partner:
    $sql = "INSERT INTO activiteit (naam,omschrijving) values ('$unieknaamP','$activiteitP')";
    $stmt = $verbinding->exec($sql);

    if ($activiteitP != 'Geen activiteit') {   //Hiermee controleer ik of partner een activiteit gekozen heeft.
        $totaalprijsP = $activiteitprijs;
    }

// Aanmaken Deelnemer voor activiteitkeuze van partner
    $verbinding->exec('SET foreign_key_checks = 0');  //Hiermee zetten we de controle van foreign keys af.
//voorbereiding om correcte activiteit id te krijgen :
    $actid = "SELECT activiteit_id FROM activiteit WHERE naam=?";
    $stmt = $verbinding->prepare($actid);
    $stmt->execute([$unieknaamP]);  //Hier rekening houden dat we uniekenaam gebruiken ipv Naam.
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    foreach ($result as $res) {
    }
//einde voorbereiding
// uitvoeren Deelnemer partner keuze:
    $sql = "INSERT INTO Deelnemer(rijksregisternummer,Activiteit_ID) values ('$registerP','$res')";
    $stmt = $verbinding->exec($sql);
//Hier sluit ik de checks nog niet af omdat ik ze nodig heb bij maaltijdkeuze


//Invoeren Gerechten partner keuze:
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//Invoeren type_id partner (Heb deze kolom gebruikt als "link" tussen persoon en gerechten
    $sql = "INSERT INTO gerechttype(type_naam) values ('$unieknaamP')";
    $stmt = $verbinding->exec($sql);

//voorbereiding om type_id van uit tabel te halen :
    $query = "SELECT type_id FROM gerechttype WHERE type_naam=?";
    $stmt = $verbinding->prepare($query);
    $stmt->execute([$unieknaamP]);  //Hier rekening houden dat we een unieke naam gebruiken.
    $uitkomst = $stmt->fetch(PDO::FETCH_ASSOC);
    foreach ($uitkomst as $typid) {
    }
//einde voorbereiding


//Controle of 3 gangen partner menu  gekozen is
    if ($gangen == 'gang3P') {
        $sql = "INSERT INTO gerechten(type_id,Voorgerecht,Visgerecht,Vleesgerecht,Dessert) values ('$typid','$voorgerechtP','$visP','$vleesP','$dessertP')";
        $stmt = $verbinding->exec($sql);

        //Controleren of de partner kreeft genomen heeft
        if ($visP == 'Gebakken kreeft') {
            $totaalprijsP = ($totaalprijsP + $maltijdprijsP3G + $kreeftprijs);
        } else {
            $totaalprijsP = ($totaalprijsP + $maltijdprijsP3G);
        }

    } //indien 4 gangen menu gekozen is
    else {
        $sql = "INSERT INTO gerechten(type_id,Voorgerecht,Soep,Visgerecht,Vleesgerecht,Dessert) values ('$typid','$voorgerechtP','$soepP','$visP','$vleesP','$dessertP')";
        $stmt = $verbinding->exec($sql);

        //Controle of partner kreeft genomen heeft
        if ($visP == 'Gebakken kreeft') {
            $totaalprijsP = ($totaalprijsP + $maltijdprijsP4G + $kreeftprijs);
        } else {
            $totaalprijsP = ($totaalprijsP + $maltijdprijsP4G);
        }
    }


//Invoeren Menu Partner
//voorbereiding om correcte GerechtID PARTNER te verkrijgen :
    $Gerechtid = "SELECT gerecht_id FROM gerechten WHERE type_id=(SELECT type_id FROM gerechttype where type_naam=?)";
    $stmt = $verbinding->prepare($Gerechtid);
    $stmt->execute([$unieknaamP]);  //Hier rekening houden dat we unieknaam gebruiken (is uniek);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    foreach ($result as $res) {
    }
//einde voorbereiding GerechtID:

//Invoeren Supplement kosten
    $sql = "INSERT INTO suplement(gerecht_id,prijs) values ('$res','$totaalprijsP')";
    $stmt = $verbinding->exec($sql);

//uitvoeren Menu:
    $sql = "INSERT INTO Menu(rijksregisternummer,Gerecht_ID,Type_ID) values ('$registerP','$res','$typid')";
    $stmt = $verbinding->exec($sql);
    $verbinding->exec('SET foreign_key_checks = 1'); //De controle op foreign keys moeten weer geactiveerd worden.
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    echo '<script>alert("Je hebt je partner toegevoegd")</script>';
    echo "<script>location.href='index.php?page=Formulier'</script>";
}
?>