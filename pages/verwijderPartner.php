<?php
$sql = "Delete  FROM activiteit where activiteit_id =(select Activiteit_ID FROM deelnemer where rijksregisternummer='$Partnerregister')";
$stmt = $verbinding->exec($sql);

$sql = "DELETE  FROM deelnemer where rijksregisternummer='$Partnerregister'";
$stmt = $verbinding->exec($sql);
$verbinding->exec('SET foreign_key_checks = 0');  //Hiermee zetten we de controle van foreign keys af.
$sql = "DELETE  FROM gerechten where type_id =(select Type_ID FROM menu where rijksregisternummer='$Partnerregister')";
$stmt = $verbinding->exec($sql);

$sql = "DELETE  FROM gerechttype where type_id=(select Type_ID FROM menu where rijksregisternummer='$Partnerregister')";
$stmt = $verbinding->exec($sql);

$sql = "DELETE  FROM suplement where gerecht_id=(select gerecht_id FROM menu where rijksregisternummer='$Partnerregister')";
$stmt = $verbinding->exec($sql);

$sql = "DELETE  FROM menu where rijksregisternummer='$Partnerregister'";
$stmt = $verbinding->exec($sql);
$sql = "UPDATE werknemer SET Partner_ID= null where rijksregisternummer='$mijnregister'";
$stmt = $verbinding->exec($sql);

$sql = "DELETE  FROM partner where rijksregisternmr='$Partnerregister'";
$stmt = $verbinding->exec($sql);
$verbinding->exec('SET foreign_key_checks = 1');  //Hiermee zetten we de controle van foreign keys aan.
echo '<script>alert("je hebt je partner verwijderd")</script>';
echo "<script>location.href='index.php?page=Formulier'</script>";
?>