
<?php
//maken pdo verbinding met db
//$verbinding gaan we overal in ons project gebruiken als db-object
DEFINE("USER", "root");
DEFINE("PASSWORD", "");

try {
    $verbinding = new
    PDO("mysql:host=localhost;dbname=personeelfeest",USER,PASSWORD);
    $verbinding->setAttribute (PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION );
    echo "Verbinding met database gemaakt<br>";
}catch(PDOException $e) {
    echo $e->getMessage();
    echo "Kon geen verbinding maken.<br>";
}
?>