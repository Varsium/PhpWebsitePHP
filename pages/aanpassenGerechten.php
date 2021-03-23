<?php
//Weergeven gerechten keuze:

$query = "select * FROM Gerechttype";
$stmt = $verbinding->prepare($query);
$stmt->execute(array());
$data = $stmt->fetch(PDO::FETCH_ASSOC);

echo '<form method="post"  style="background-color: whitesmoke">
    <table border="5">';
foreach ($data as $test)
    echo '<th>'.$test['type_naam'].'</th>';

echo '<tr>';
foreach ($data as $colom) {
    echo '<th>' . $colom . '</th>';
}
echo '</tr></table></form>';
//Weergeven om aanpassing te doen :
if ($Bevestigd != "JA") {
    echo '<form method="post" name="menu" style="background-color: whitesmoke">';
include ("Ophalengerechten.php");
        echo'<br><input type="submit"  value="Menu aanpassen" name="aanpassenmenu">
        </form>';
}
if (isset($_POST['aanpassenmenu'])) {
    //Variabelen form;
    $gangen = htmlspecialchars($_POST['gangenmenu']);
    $voorgerecht = htmlspecialchars($_POST['voorgerecht']);
    $soep = htmlspecialchars($_POST['soepen']);
    $vis = htmlspecialchars($_POST['visgerechten']);
    $vlees = htmlspecialchars($_POST['vleesgerechten']);
    $dessert = htmlspecialchars($_POST['desserts']);


    //Controle of 3 gangen menu  gekozen is
    if ($gangen == 'gang3') {
        $sql = "UPDATE gerechten SET Voorgerecht='$voorgerecht',Soep='',Visgerecht='$vis',Vleesgerecht='$vlees',Dessert='$dessert' where gerecht_id =(select Gerecht_ID FROM menu where rijksregisternummer='$mijnregister')";
        $stmt = $verbinding->exec($sql);
        if ($vis == 'Gebakken kreeft') {
            $totaalprijs += $maltijdprijs3G + $kreeftprijs;
        } else {
            $totaalprijs += $maltijdprijs3G;
        }
    }
    //indien 4 gangen menu gekozen is
    else {
        $sql = "UPDATE gerechten SET Voorgerecht='$voorgerecht',Soep='$soep',Visgerecht='$vis',Vleesgerecht='$vlees',Dessert='$dessert' where gerecht_id =(select Gerecht_ID FROM menu where rijksregisternummer='$mijnregister')";
        $stmt = $verbinding->exec($sql);

        //Hiermee controleer ik of het Kreeft is zodat ik de prijs indien nodig kan aanpassen
        if ($vis == 'Gebakken kreeft') {
            $totaalprijs += $maltijdprijs4G + $kreeftprijs;
        } else {
            $totaalprijs += $maltijdprijs4G;
        }
    }

    $sql = "SELECT gepensioneerd FROM werknemer where rijksregisternummer=? ";
    $stmt = $verbinding->prepare($sql);
    $stmt->execute(array($mijnregister));
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($data['gepensioneerd'] == 1) {
        $totaalprijs += $pensioenprijs;
    }


    //Invoeren Supplement kosten
    $sql = "UPDATE suplement SET prijs='$totaalprijs' where gerecht_id =(select Gerecht_ID FROM menu where rijksregisternummer='$mijnregister')";
    $stmt = $verbinding->exec($sql);
}

?>