<?php

echo '
                    <div>
                        <hr>
                        Gelieve hier uw menu samen te stellen:<br>
                        <input type="radio" name="gangenmenu" value="gang3P" id="3gang" class="gangen3P" checked> 4
                        gangen menu
                        <input type="radio" name="gangenmenu" value="gang4P" id="4gang" class="gangen4P"> 5 gangen menu
                        <br>
                        Voorgerecht:<select name="voorgerecht" id="voorgerecht">';
$query = "select * from gerechten where Type_ID= 'Voor'";
$stmt = $verbinding->prepare($query);
$stmt->execute();
foreach ($stmt as $gerecht) {
    echo '<option>' . $gerecht['Gerechtnaam'] . '</option>';
}
echo '     </select>
                        <div id="soepje2">
                            Soep: <select name="soepen" id="Soepen">
                                <option></option>';
$query = "select * from gerechten where Type_ID= 'Soep'";
$stmt = $verbinding->prepare($query);
$stmt->execute();
foreach ($stmt as $gerecht) {
    echo '<option>' . $gerecht['Gerechtnaam'] . '</option>';
}
echo ' </select>
                        </div>
                        Visgerecht: <select name="visgerechten" id="visgerechten">';
$query = "select * from gerechten where Type_ID= 'Vis'";
$stmt = $verbinding->prepare($query);
$stmt->execute();
foreach ($stmt as $gerecht) {
    echo '<option>' . $gerecht['Gerechtnaam'] . '</option>';
}
echo '</select>
                        <br>
                        Vleesgerecht: <select name="vleesgerechten" id="vleessgerechten">';
$query = "select * from gerechten where Type_ID= 'Vlee'";
$stmt = $verbinding->prepare($query);
$stmt->execute();
foreach ($stmt as $gerecht) {
    echo '<option>' . $gerecht['Gerechtnaam'] . '</option>';
}

echo ' </select>
                        <br>
                       Dessert: <select name="desserts" id="Desserts">';
$query = "select * from gerechten where Type_ID= 'Dess'";
$stmt = $verbinding->prepare($query);
$stmt->execute();
foreach ($stmt as $gerecht) {
    echo '<option>' . $gerecht['Gerechtnaam'] . '</option>';
}

echo '</select>';
?>