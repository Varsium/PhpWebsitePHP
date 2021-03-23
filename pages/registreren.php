<?php
include("mailen.php");
include("db_Website.php");
?><!DOCTYPE html>
<html lang="nl">
<head>
    <title>Website</title>

</head>
<body>
<div class="content">

    <h1 id="hoofdform"> Registreer hier, alsook maak je keuze van activiteiten<br></h1>
    <form method="post" name="registreren">

        <fieldset>
            <p>Registreren</p>
            <input type="text" required name="voornaam" placeholder="voornaam"/>
            <input type="text" required name="achternaam" placeholder="achternaam"/>
            <input type="text" required name="Register" placeholder="Rijksregister"/>
            <input type="text" required name="Telefoonnummer" placeholder="telefoonnummer"/>
            <input type="email" required name="email" placeholder="email"/>
            <input type="password" required name="wachtwoord" placeholder="wachtwoord"/>
            <hr>
            <?php
            include("OphalenActiviteit.php");
            ?>
            <input type="checkbox" id="Oud" value="1" name="pensioen" class="radiosenior">Gepensioneerd<br>
            <?php
            include("Ophalengerechten.php")
            ?>
            <br><input type="submit" id="submit" name="submit" value="Registreren"/>
        </fieldset>
    </form>
    Als je al een account hebt kun je hier klikken om in te loggen:
    <form><input type="submit" name="terug" value="Inloggen"></form>
    </main>
</div>
</body>
</html>
<?php
if (isset($_POST["terug"])) {
    echo "<script>location.href='index.php'</script>";
}
if (isset($_POST["submit"])) {

    //Gegevens persoon :
    $voornaam = htmlspecialchars($_POST['voornaam']);
    $achternaam = htmlspecialchars($_POST['achternaam']);
    $register = htmlspecialchars($_POST['Register']);
    $unieknaam = $voornaam . $register; //Deze maak ik aan om unieke namen te creeren
    $werknemerID = substr($achternaam, 0, 2) . substr($voornaam, 0, 2)
        . substr($register, 4, 2) . substr($register, 2, 2) . substr($register, 0, 2); //Hiermee maak ik Accountnaam waarop je kan inloggen en controleren ,  door middel van sub kan begin positie en lengte bepalen.
    $telefoon = htmlspecialchars($_POST['Telefoonnummer']);
    $email = htmlspecialchars($_POST['email']);
    $wachtwoord = htmlspecialchars($_POST['wachtwoord']);
    $wachtwoordHash = password_hash($wachtwoord, PASSWORD_DEFAULT);   //Om het wachtwoord "onleesbaar" opslaan

    //Activiteiten:
    $activiteit = htmlspecialchars($_POST['activiteit']);


    //Menu:
    $gangen = htmlspecialchars($_POST['gangenmenu']);
    $voorgerecht = htmlspecialchars($_POST['voorgerecht']);
    $soep = htmlspecialchars($_POST['soepen']);
    $vis = htmlspecialchars($_POST['visgerechten']);
    $vlees = htmlspecialchars($_POST['vleesgerechten']);
    $dessert = htmlspecialchars($_POST['desserts']);

    //Gepensioneerd     Hier mee zet ik de waarde op true of false
    if (empty($_POST['pensioen'])) {
        $gepensioneerd = false;
    } else {
        $gepensioneerd = true;

    }

    // controleer of Rijksregister al bestaat (deze is uniek)
    $sql = "SELECT * FROM Werknemer WHERE rijksregisternummer = ?";
    $stmt = $verbinding->prepare($sql);
    $stmt->execute(array($register));
    $resultaat = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($resultaat) {
        echo '<script>alert("Deze werknemer is al geregistreerd")</script>';

        //Indien Rijkregister nog niet bestaat wordt dit uitgevoerd.
    } else {
        $TeControlerenGetal = substr($register, 0, 9);
        $Controlegetal = substr($register, 9, 2);
        $ModuloControle = ($TeControlerenGetal % 97);

        if ($Controlegetal == (97 - $ModuloControle)) {

            //Invoeren alle gegevens Werknemer
            $sql = "INSERT INTO Werknemer (rijksregisternummer, WerknemerID,
		Voornaam,Naam,Telefoon,email,gepensioneerd,wachtwoord)
		 values ('$register','$werknemerID','$voornaam','$achternaam','$telefoon','$email','$gepensioneerd','$wachtwoordHash')";
            $stmt = $verbinding->exec($sql);


            // Aanmaken Deelnemer voor activiteitkeuze
            $verbinding->exec('SET foreign_key_checks = 0');  //Hiermee zetten we de controle van foreign keys af.
            //voorbereiding om correcte activiteit id te krijgen :
            $actid = "SELECT activiteit_id FROM activiteit WHERE naam=?";
            $stmt = $verbinding->prepare($actid);
            $stmt->execute([$activiteit]);  //Hier rekening houden dat we uniekenaam gebruiken ipv Naam.
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            foreach ($result as $res) {
            }
            //einde voorbereiding
            // uitvoeren Deelnemer keuze:
            $sql = "INSERT INTO Deelnemer(rijksregisternummer,Activiteit_ID) values ('$register','$res')";
            $stmt = $verbinding->exec($sql);
            //Hier sluit ik de checks nog niet af omdat ik ze nodig heb bij maaltijdkeuze

            //voorbereiding om type_id van voorgerecht uit te halen :
            $query = "SELECT gerecht_id FROM gerechten WHERE Gerechtnaam=?";
            $stmt = $verbinding->prepare($query);
            $stmt->execute([$voorgerecht]);
            $uitkomst = $stmt->fetch(PDO::FETCH_ASSOC);
            foreach ($uitkomst as $voorge) {
            }
            $stmt->execute([$soep]);
            $uitkomst = $stmt->fetch(PDO::FETCH_ASSOC);
            foreach ($uitkomst as $soe) {
            }
            $stmt->execute([$vis]);
            $uitkomst = $stmt->fetch(PDO::FETCH_ASSOC);
            foreach ($uitkomst as $visgerecht) {
            }
            $stmt->execute([$vlees]);
            $uitkomst = $stmt->fetch(PDO::FETCH_ASSOC);
            foreach ($uitkomst as $vleesgerecht) {
            }
            $stmt->execute([$dessert]);
            $uitkomst = $stmt->fetch(PDO::FETCH_ASSOC);
            foreach ($uitkomst as $dess) {
            }
            $verbinding->exec('SET foreign_key_checks = 0'); //De controle op foreign keys moeten weer geactiveerd worden.
            //einde voorbereiding
            $sql = "INSERT INTO menu(rijksregisternummer,Gerecht_ID) values ('$register','$voorge')";
            $stmt = $verbinding->exec($sql);
            if ($soe != null) {
                $sql = "INSERT INTO menu(rijksregisternummer,Gerecht_ID) values ('$register','$soe')";
                $stmt = $verbinding->exec($sql);
            }
            $sql = "INSERT INTO menu(rijksregisternummer,Gerecht_ID) values ('$register','$visgerecht')";
            $stmt = $verbinding->exec($sql);
            $sql = "INSERT INTO menu(rijksregisternummer,Gerecht_ID) values ('$register','$vleesgerecht')";
            $stmt = $verbinding->exec($sql);
            $sql = "INSERT INTO menu(rijksregisternummer,Gerecht_ID) values ('$register','$dess')";
            $stmt = $verbinding->exec($sql);


            $verbinding->exec('SET foreign_key_checks = 1'); //De controle op foreign keys moeten weer geactiveerd worden.
        } else {
            echo '<script>alert("Je rijksregister klopt niet")</script>';
        }
    }
    if ($stmt) {
        echo '<script>alert("Nieuw account aangemaakt")</script>';
        $onderwerp = "Nieuwe account";
        $bericht = "Geachte  $werknemerID dit is uw inlognaam, bij deze bevestigen we je nieuwe account. uw kunt nu inloggen om u gegevens te bekijken, bewerken of bevestigen";
        mailen($email, $werknemerID, $onderwerp, $bericht);
        echo "<script>location.href='index.php?page=inloggen'</script>";

    } else { //Indien niet alles gelukt is krijg je hiervan melding
        echo '<script>alert("Oops er liep iets verkeerd")</script>';
    }

}

?>
