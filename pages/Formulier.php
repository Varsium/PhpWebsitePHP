<?php
session_start();
include("mailen.php");
include("db_Website.php");
include("Prijzen.php");
include("opvragenGegevens.php");

if (isset($_SESSION["USER_ID"])) {
    echo '<h1>welkom ' . $mezelf['Voornaam'] . ' hier kan u uw gegevens bewerken</h1><form method="post">';

    //alles voor werknemer wordt vanaf hier behandeld.
    include("aanpassenWerknemergegevens.php");
    include("aanpassenActiviteit.php");
    include("aanpassenGerechten.php");


    //------------------------------------------------------------------------------------------------------------------------
    //Alles voor partner wordt vanaf hier behandeld.
    if ($RegisterPartner != null) {
        include("aanpassenPartnerGegevens.php");
        include("aanpassenPartnerActiviteit.php");
        include("aanpassenMenuPartner.php");

        if ($Bevestigd != "JA") {
            echo '<br><hr><br><form method="post"><input type="submit" value="Verwijder Partner" name="VerwijderPartner">';
        }
        if (isset($_POST['VerwijderPartner'])) {
            include("verwijderPartner.php");
        }
    }
    if (isset($_POST["toevoegenpartner"])) {
        ?>
        <hr>
        <form method="post" name="registreren">
            <fieldset>
                <div>
                    <hr>
                    <p><b>Hier kan de partner zijn gegevens invullen</b>.</p>
                    <input type="radio" name="activiteit2" class="activiteit" value="Workshop Disco" checked>Workshop
                    Disco
                    <input type="radio" name="activiteit2" class="activiteit" value="Workshop Cocktails">Workshop
                    Cocktails
                    <input type="radio" name="activiteit2" class="activiteit" value="Geen activiteit"> Geen
                    activiteit
                    <br>
                    <input placeholder="Voornaam" type="text" name="voornaam2" id="Voornaam2">
                    <input placeholder="Naam" type="text" name="naam2" id="naam2">
                    <input placeholder="Rijksregister" type="text" name="Register2" id="Register2">
                    <br>
                    Gelieve hier uw menu samen te stellen:<br>
                    <input type="radio" name="gangenmenuP" value="gang3P" id="3gang" class="gangen3P" checked> 3
                    gangen menu
                    <input type="radio" name="gangenmenuP" value="gang4P" id="4gang" class="gangen4P"> 4 gangen menu
                    <br>
                    Voorgerecht:<select name="voorgerecht2" id="voorgerecht2">
                        <option>Meloen met gandaham</option>
                        <option>Rundvlees pasteitje</option>
                        <option>Zalmcarpaccio</option>
                        <option>Garnaalkroketten</option>
                    </select>
                    <div id="soepje2">
                        Soep: <select name="soepen2" id="Soepen2">
                            <option></option>
                            <option>Heldere groentensoep</option>
                            <option>Gevulde kippensoep</option>
                            <option value="Vissoep">Vissoep</option>
                            <option value="Kabeljauwsoep">Kabeljauwsoep</option>
                        </select>
                    </div>
                    Visgerecht: <select name="visgerechten2" id="visgerechten2">
                        <option>Zalm met kappertjessaus</option>
                        <option>Schelvis met groentereepjes</option>
                        <option id="Kreeft2">Gebakken kreeft</option>
                        <option>Vispannetje</option>
                    </select>
                    <br>
                    Vleesgerecht: <select name="vleesgerechten2" id="vleessgerechten2">
                        <option>Kippenreepjes</option>
                        <option>Ossenhaaspuntjes</option>
                        <option>Black angus</option>
                        <option>Varkenswangetjes</option>
                    </select>
                    <br>
                    <select name="desserts2" id="Desserts2">
                        <option>Chocolade baverois</option>
                        <option>Ijstaart</option>
                        <option>Fruitsalade</option>
                        <option>Stoofperencrumble</option>
                    </select>
                </div>
                <input type="submit" id="submit" name="AanmakenPartner" value="Partner aanmaken"/>
            </fieldset>
        </form>
        <?php
    }
    if (isset($_POST["AanmakenPartner"])) {
        include("aanmakenPartner.php");
    }
    if ($Bevestigd != "JA") {
            echo '<br><form method="post"><input style="background-color: red" type="submit" value="Bevestig alle gegevens" name="Bevestigen"><b>Dit is zowel voor uzelf en partner, OPGELET! dit is definitief hierna kan je niet meer aanpassen</b> ';
    }
    if (isset($_POST["Bevestigen"])) {
        $sql = "UPDATE werknemer SET Bevestigd='JA' where rijksregisternummer='$mijnregister'";
        $stmt = $verbinding->exec($sql);
        echo '<script>alert("Je hebt bevestigd")</script>';
        include("Bericht.php");
        echo "<script>location.href='index.php?page=Formulier'</script>";
    }

}
?>