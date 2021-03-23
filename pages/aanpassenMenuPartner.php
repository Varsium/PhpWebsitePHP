<?php
//Weergeven gerechten keuze:

$query = "select * FROM Gerechten where gerecht_id =(select Gerecht_ID FROM Menu where rijksregisternummer=?)";
$stmt = $verbinding->prepare($query);
$stmt->execute(array($Partnerregister));
$data = $stmt->fetch(PDO::FETCH_ASSOC);
echo '<form method="post" id="Activiteit" style="background-color: whitesmoke">
    <table border="5">';
for ($i = 2; $i < ($stmt->columnCount()); $i++) {
    $head = $stmt->getColumnMeta($i);
    echo '<th>' . $head["name"] . '</th>';
}
echo '<tr>';
foreach ((array_slice($data, 2, count($data))) as $colom) {
    echo '<td>' . $colom . '</td>';
}
echo '</tr></table></form>';
//Weergeven om aanpassing te doen :
if ($Bevestigd != "JA") {
    echo '<form method="post" name="menu" style="background-color: whitesmoke">
 <input type="radio" name="gangenmenuP" value="gang3" id="gang3" class="gangen3" checked> 3 gangen menu
        <input type="radio" name="gangenmenuP" value="gang4" class="gangen4"> 4 gangen menu
        <br>
        Voorgerecht:<input placeholder="Voorgerecht" type="text" required list="Gerechten" name="voorgerechtP" id="voorgerecht">
        <datalist id="Gerechten">
          <option value="Meloen met gandaham"/>
          <option value="Rundvlees pasteitje"/>
          <option value="Zalmcarpaccio"/>
          <option value="Garnaalkroketten"/>
        </datalist>
        <br>
        <div id="soepje">Soep:<input placeholder="Soep" type="text" list="soep" name="soepenP" id="Soepen">
          <datalist id="soep">
            <option value="Heldere groentensoep"/>
            <option value="Gevulde kippensoep"/>
            <option value="Vissoep"/>
            <option value="Kabeljauwsoep"/>
          </datalist>
        </div>

       Visgerecht: <input required placeholder="Visgerechten" type="text" list="vis" name="visgerechtenP" id="visgerechten">
          <datalist id="vis">
            <option value="Zalm met kappertjessaus"/>
            <option value="Schelvis met groentereepjes"/>
            <option id="Kreeft" value="Gebakken kreeft"/>
            <option value="Vispannetje "/>
          </datalist> <br>
          
     Vleesgerecht: <input required placeholder="Vleesgerechten" type="text" list="vlees" name="vleesgerechtenP" id="vleessgerechten">
          <datalist id="vlees">
            <option value="Kippenreepjes"/>
            <option value="Ossenhaaspuntjes"/>
            <option value="Black angus"/>
            <option value="Varkenswangetjes "/>
          </datalist> <br>
          
      Dessert:  <input placeholder="Dessert" type="text" required list="Dessert" name="dessertsP" id="Desserts">
        <datalist id="Dessert">
          <option value="Chocolade baverois"/>
          <option value="Ijstaart"/>
          <option value="Fruitsalade"/>
          <option value="Stoofperencrumble"/>
        </datalist>
        <br><input type="submit"  value="Menu aanpassen" name="aanpassenmenuP">
        </form>';
}
if (isset($_POST['aanpassenmenuP'])) {
    //Variabelen form;
    $gangen = htmlspecialchars($_POST['gangenmenuP']);
    $voorgerecht = htmlspecialchars($_POST['voorgerechtP']);
    $soep = htmlspecialchars($_POST['soepenP']);
    $vis = htmlspecialchars($_POST['visgerechtenP']);
    $vlees = htmlspecialchars($_POST['vleesgerechtenP']);
    $dessert = htmlspecialchars($_POST['dessertsP']);


    //Controle of 3 gangen menu  gekozen is
    if ($gangen == 'gang3') {
        $sql = "UPDATE gerechten SET Voorgerecht='$voorgerecht',Soep='',Visgerecht='$vis',Vleesgerecht='$vlees',Dessert='$dessert' where gerecht_id =(select Gerecht_ID FROM menu where rijksregisternummer='$Partnerregister')";
        $stmt = $verbinding->exec($sql);
        if ($vis == 'Gebakken kreeft') {
            $totaalprijsP += $maltijdprijsP3G + $kreeftprijs;
        } else {
            $totaalprijsP += $maltijdprijsP3G;
        }
    }  //indien 4 gangen menu gekozen is
    else {
        $sql = "UPDATE gerechten SET Voorgerecht='$voorgerecht',Soep='$soep',Visgerecht='$vis',Vleesgerecht='$vlees',Dessert='$dessert' where gerecht_id =(select Gerecht_ID FROM menu where rijksregisternummer='$Partnerregister')";
        $stmt = $verbinding->exec($sql);

        //Hiermee controleer ik of het Kreeft is zodat ik de prijs indien nodig kan aanpassen
        if ($vis == 'Gebakken kreeft') {
            $totaalprijsP += $maltijdprijsP4G + $kreeftprijs;
        } else {
            $totaalprijs += $maltijdprijsP4G;
        }
    }
    $query = "select * FROM activiteit where activiteit_id =(select Activiteit_ID FROM deelnemer where rijksregisternummer=?)";
    $stmt = $verbinding->prepare($query);
    $stmt->execute(array($Partnerregister));
    $activiteit = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($activiteit["omschrijving"] != "Geen activiteit") {
        $eindprijs = $activiteitprijs + $totaalprijsP;
    } else {
        $eindprijs = $totaalprijsP;
    }
        //Invoeren Supplement kosten
        $sql = "UPDATE suplement SET prijs='$eindprijs' where gerecht_id =(select Gerecht_ID FROM menu where rijksregisternummer='$Partnerregister')";
    $stmt = $verbinding->exec($sql);

} ?>
