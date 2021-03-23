<?php
include ("opvragenGegevens.php");
//Werknemer gegevens weergeven en aanpassen indien gewenst
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------
$query = "select * from werknemer where rijksregisternummer =?";
$stmt = $verbinding->prepare($query);
$stmt->execute(array($mijnregister));
$data = $stmt->fetch(PDO::FETCH_ASSOC);
echo '<form method="post" id="gegevens" style="background-color: whitesmoke">
    <table border="5">';
for ($i = 0; $i < ($stmt->columnCount()) - 3; $i++) {
    $head = $stmt->getColumnMeta($i);
    echo '<th>' . $head["name"] . '</th>';

}
echo '<tr>';
foreach (array_slice($data, 0, count($data) - 3) as $colom) { //Ik gebruik dit omdat de andere 2 kolommen niet gezien moeten worden.
    echo '<td>' . $colom . '</td>';
}
echo '</table></form>';
if ($Bevestigd != "JA") {
    echo '</tr></table></form>
                <form method="post" style="background-color: whitesmoke">' .
        'Rijksregister<input required name="register" type="number" value="' . $mijnregister . '"><br>' .
        'Voornaam<input required name="voornaam" type="text" value="' . $mijnVoornaam . '"><br>' .
        'Achternaam<input required name="achternaam" type="text" value="' . $mijnAchternaam . '"><br>' .
        'Telefoon<input required name="telefoon" type="number" value="' . $mijnTelefoon . '"><br>' .
        'Email<input required name="email" type="text" value="' . $mijnEmail . '"><br>' .
        'Gepensioneerd<select name="gepensioneerd"><option value="">Nee</option><option value="1">JA</option></select><br>'
        . '<input type="submit" name="aanpassengegevens" value="Gegevens aanpassen"></form><hr>';
} else {
    echo "Je hebt al je gegevens bevestigd je kan deze nog inzien maar niet meer aanpassen";
}

if ((isset($_POST['aanpassengegevens'])) && (isset($_POST['register'])) && (isset($_POST['voornaam'])) && (isset($_POST['achternaam'])) && (isset($_POST['telefoon'])) && (isset($_POST['email'])) && (isset($_POST['gepensioneerd']))) {
    $updateregister = htmlspecialchars($_POST['register']);
    $updateVoornaam = htmlspecialchars($_POST['voornaam']);
    $updateAchternaam = htmlspecialchars($_POST['achternaam']);
    $updateTelefoon = htmlspecialchars($_POST['telefoon']);
    $updateEmail = htmlspecialchars($_POST['email']);
    $updateGepensioneerd = htmlspecialchars($_POST['gepensioneerd']);
    $updateunieknaam = $updateVoornaam . $updateregister; //Deze maak ik aan om unieke namen te creeren

    //Hiermee controleer ik of het rijksregisternummer hetzelfde is gebleven. zo niet veranderd mijn invoer.
    if ($updateregister == $mijnregister) {
        $sql = "UPDATE Werknemer SET
		Voornaam='$updateVoornaam',Naam='$updateAchternaam',Telefoon='$updateTelefoon',
		email='$updateEmail',gepensioneerd='$updateGepensioneerd' where rijksregisternummer='$mijnregister'";
        $stmt = $verbinding->exec($sql);
        if ($stmt) {
            echo "Je gegevens werden aangepast";
        }
    } else {
        $TeControlerenGetal = substr($mijnregister, 0, 9);
        $Controlegetal = substr($mijnregister, 9, 2);
        $ModuloControle = ($TeControlerenGetal % 97);

        if ($Controlegetal == (97 - $ModuloControle)) {
            $verbinding->exec('SET foreign_key_checks = 0');  //Hiermee zetten we de controle van foreign keys af.
            $sql = "UPDATE Werknemer SET rijksregisternummer='$updateregister',
		Voornaam='$updateVoornaam',Naam='$updateAchternaam',Telefoon='$updateTelefoon',
		email='$updateEmail',gepensioneerd='$updateGepensioneerd' where rijksregisternummer='$mijnregister'";
            $stmt = $verbinding->exec($sql);

            //Bijkomende registerplaatsen ook correct aanpassen.
            //Rijksregister in menu:
            $sql = "UPDATE menu SET rijksregisternummer='$updateregister'Where rijksregisternummer='$mijnregister'";
            $stmt = $verbinding->exec($sql);

            //rijksregister in Deelnemer:
            $sql = "UPDATE deelnemer SET rijksregisternummer='$updateregister'Where rijksregisternummer='$mijnregister'";
            $stmt = $verbinding->exec($sql);
            $verbinding->exec('SET foreign_key_checks = 1');  //Hiermee zetten we de controle van foreign keys aan.
        }
    }
}
?>