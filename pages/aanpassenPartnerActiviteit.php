<?php
//Weergeven welke activiteit Men gekozen heeft en Aanpassen indien Nodig:
$query = "select * FROM activiteit where activiteit_id =(select Activiteit_ID FROM deelnemer where rijksregisternummer=?)";
$stmt = $verbinding->prepare($query);
$stmt->execute(array($Partnerregister));
$data = $stmt->fetch(PDO::FETCH_ASSOC);
echo '<form method="post" id="Activiteit" style="background-color: whitesmoke">
    <table border="5">';
for ($i = 1; $i < ($stmt->columnCount()); $i++) {
    $head = $stmt->getColumnMeta($i);
    echo '<th>' . $head["name"] . '</th>';
}
echo '<tr>';
foreach ((array_slice($data, 1, count($data))) as $colom) {
    echo '<td>' . $colom . '</td>';
}
echo '</tr></table></form>';
if ($Bevestigd != "JA") {
    echo ' <form method="post" style="background-color: whitesmoke">' .
        'Activiteitskeuze<select name="Activiteit"><option value="Workshop Disco">Workshop Disco</option><option value="Workshop Cocktails">Workshop Cocktails</option><option value="Geen activiteit">Geen activiteit</option></select><br>'
        . '<input type="submit" name="aanpassenActiviteit" value="Activiteit aanpassen"></form><hr>';
}

if ((isset($_POST['aanpassenActiviteit'])) && (isset($_POST['Activiteit']))) {
    $activiteitkeuze = $_POST['Activiteit'];
    $query = "UPDATE activiteit SET omschrijving='$activiteitkeuze' where activiteit_id =(select Activiteit_ID FROM deelnemer where rijksregisternummer='$Partnerregister')";
    $stmt = $verbinding->exec($query);
    $query = "SELECT prijs from suplement where gerecht_id=(select Gerecht_ID FROM menu where rijksregisternummer=?)";
    $stmt = $verbinding->prepare($query);
    $stmt->execute((array($Partnerregister)));
    $prijs = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($activiteitkeuze != $data["omschrijving"]) {
        if (($activiteitkeuze == "Geen activiteit") && ($data["omschrijving"] != "Geen activiteit")) {
            $uppdateprijs = ($prijs["prijs"] - $activiteitprijs);
            $query = "UPDATE suplement SET prijs='$uppdateprijs' where gerecht_id =(select Gerecht_ID FROM menu where rijksregisternummer='$Partnerregister')";
            $stmt = $verbinding->exec($query);
        } elseif (($activiteitkeuze != "Geen activiteit") && ($data["omschrijving"] == "Geen activiteit")) {
            $uppdateprijs = ($prijs["prijs"] + $activiteitprijs);
            $query = "UPDATE suplement SET prijs='$uppdateprijs' where gerecht_id =(select Gerecht_ID FROM menu where rijksregisternummer='$Partnerregister')";
            $stmt = $verbinding->exec($query);
        }
    }
}


?>
