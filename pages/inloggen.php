<?php
session_start();
include "db_Website.php";
?>
<div class="invulbox">
    <form class="modal-content" name="inloggen" method="POST"
          enctype="multipart/form-data">
        <p id="page_titel">Inloggen</p>
        <div class="invulbox">
            <label><b>Login-naam</b></label>
            <input id="tekst" type="text" name="WerknemerID"
                   placeholder="Werknemer ID ingeven"/>
            <input id="wachtwoord" type="password" name="wachtwoord"
                   placeholder="wachtwoord"/>
            <input type="submit"
                   id="submit" name="submit"
                   value="verzenden"/>
        </div>

        <div id="onderblok" class="invulbox">
        </div>

        <input type="submit" value="Wachtwoord vergeten" name="vergeten">
        <input type="submit" value="Registreren" name="registreren">
    </form>
</div>
<?php
if (isset($_POST["registreren"])) {
    echo "<script>location.href='index.php?page=registreren'</script>";
} elseif (isset($_POST["vergeten"])) {
    echo "<script>location.href='index.php?page=wachtwoord_vergeten'</script>";
}
if (isset($_POST["submit"])) {
    $Werknemer = htmlspecialchars($_POST['WerknemerID']);
    $wachtwoord = htmlspecialchars($_POST['wachtwoord']);
    $wachtwoordHash = password_hash($wachtwoord, PASSWORD_DEFAULT);
    try {
        $sql = "SELECT * FROM Werknemer WHERE WerknemerID = ?";
        $stmt = $verbinding->prepare($sql);
        $stmt->execute(array($Werknemer));
        $resultaat = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($resultaat) {
            $wachtwoordInDatabase = $resultaat["wachtwoord"];
            if (password_verify($wachtwoord, $wachtwoordInDatabase)) {
                $_SESSION["ID"] = session_id();
                $_SESSION["USER_ID"] = $resultaat['WerknemerID'];
                $_SESSION["USER_NAAM"] = $resultaat['Voornaam'];
                $_SESSION["EMAIL"] = $resultaat['email'];
                $_SESSION["STATUS"] = "ACTIEF";
                echo "<script>location.href='index.php?page=Home'</script>";
            } else {
                echo "Je wachtwoord is verkeerd";
            }
        } else {
            echo "Je loginnaam is verkeerd";
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

}
?>
