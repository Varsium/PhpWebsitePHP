<?php

echo' Keuzeactiviteit: <select name="activiteit">';
$query = "select * from Activiteit";
$stmt = $verbinding->prepare($query);
$stmt->execute();
foreach ($stmt as $activiteit) {
    echo '<option>' . $activiteit['naam'] . '</option>';
}
echo ' </select>';
?>
