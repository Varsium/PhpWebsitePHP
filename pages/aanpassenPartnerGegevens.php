<?php
$query = "select * from partner where rijksregisternmr =?";
$stmt = $verbinding->prepare($query);
$stmt->execute(array($Partnerregister));
$data = $stmt->fetch(PDO::FETCH_ASSOC);
if ($Bevestigd == "JA") {
    echo "<b>alle gegevens werden al bevestigd je kan ze nog inzien maar niet meer aanpassen.</b>";
}
for ($i = 0; $i < ($stmt->columnCount()); $i++) {
    $head = $stmt->getColumnMeta($i);
    echo '<th>' . $head["name"] . '</th>';
}
echo '<tr>';
foreach ($data as $colom) {
    echo '<td>' . $colom . '</td>';
}
echo '</tr>';

echo '</tr></table></form>';
if ($Bevestigd != "JA") {
    echo '<form method="post" style="background-color: whitesmoke">
Rijksregister<input name="registerP" type="number" value="' . $Partnerregister . '"><br>' .
        'Voornaam<input name="voornaamP" type="text" value="' . $PartnerVoornaam . '"><br>' .
        'Achternaam<input name="achternaamP" type="text" value="' . $PartnerAchternaam . '"><br>'
        . '<input type="submit" name="aanpassengegevensPartner" value="Gegevens aanpassen"></form>';
}
if ((isset($_POST['aanpassengegevensPartner'])) && (isset($_POST['register'])) && (isset($_POST['voornaam'])) && (isset($_POST['achternaam']))) {
    $updateregisterP = htmlspecialchars($_POST['registerP']);
    $updateVoornaamP = htmlspecialchars($_POST['voornaamP']);
    $updateAchternaamP = htmlspecialchars($_POST['achternaamP']);
    if ($updateregisterP == $Partnerregister) {
        $sql = "UPDATE partner SET  Voornaam='$updateVoornaamP',naam='$updateAchternaamP' where rijksregisternmr='$Partnerregister'";
        $stmt = $verbinding->exec($sql);
        if ($stmt) {
            echo "Je gegevens werden aangepast";
        }
    } else {
        $TeControlerenGetal = substr($updateregisterP, 0, 9);
        $Controlegetal = substr($updateregisterP, 9, 2);
        $ModuloControle = ($TeControlerenGetal % 97);
            if ($Controlegetal == (97 - $ModuloControle)) {
                $verbinding->exec('SET foreign_key_checks = 0');  //Hiermee zetten we de controle van foreign keys af.
                $sql = "UPDATE partner SET rijksregisternmr='$updateregisterP',
		            Voornaam='$updateVoornaamP',Naam='$updateAchternaamP' where rijksregisternummer='$Partnerregister'";
                $stmt = $verbinding->exec($sql);

                //Bijkomende registerplaatsen ook correct aanpassen.
                //Rijksregister in menu:
                $sql = "UPDATE menu SET rijksregisternummer='$updateregisterP'Where rijksregisternummer='$Partnerregister'";
                $stmt = $verbinding->exec($sql);

                //rijksregister in Deelnemer:
                $sql = "UPDATE deelnemer SET rijksregisternummer='$updateregisterP'Where rijksregisternummer='$Partnerregister'";
                $stmt = $verbinding->exec($sql);
                $verbinding->exec('SET foreign_key_checks = 1');  //Hiermee zetten we de controle van foreign keys aan.
            }
        }
    }
 ?>