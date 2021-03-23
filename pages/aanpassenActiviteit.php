<?php
//Weergeven welke activiteit Men gekozen heeft en Aanpassen indien Nodig:
$query = "select * FROM activiteit where activiteit_id =(select Activiteit_ID FROM deelnemer where rijksregisternummer=?)";
$stmt = $verbinding->prepare($query);
$stmt->execute(array($mijnregister));
$data = $stmt->fetch(PDO::FETCH_ASSOC);
echo '<form method="post" id="Activiteit" style="background-color: whitesmoke">
    <table border="5">';
for ($i = 1; $i < ($stmt->columnCount()); $i++) {
    $head = $stmt->getColumnMeta($i);
    echo '<th>' . $head["name"] . '</th>';
}
echo '<tr>';
foreach (array_slice($data, 1, count($data)) as $colom) {
    echo '<td>' . $colom . '</td>';
}
echo '</table></form>';
if ($Bevestigd != "JA") {
    echo '<form style="background-color: whitesmoke" method="post">';
    include("OphalenActiviteit.php");
    echo '<input type="submit" name="aanpassenActiviteit" value="Activiteit aanpassen"></form><hr>';

}
if (isset($_POST['aanpassenActiviteit'])){
    $activiteitkeuze = $_POST['activiteit'];
    $query = "select activiteit_id from activiteit where naam=?";
    $stmt = $verbinding->prepare($query);
    $stmt->execute(array($activiteitkeuze));
    $uitkomst= $stmt->fetch(PDO::FETCH_ASSOC);
    $activiteitid=$uitkomst['activiteit_id'];

    $query = "UPDATE deelnemer SET Activiteit_ID=? where rijksregisternummer ='$mijnregister'";
    $stmt = $verbinding->prepare($query);
    $stmt->execute(array($activiteitid));
    if ($stmt) {
        echo "Je gegevens werden aangepast";
    }
}
?>
