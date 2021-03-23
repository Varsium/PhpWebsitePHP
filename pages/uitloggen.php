<?php
session_start();
$_SESSION = [];
session_destroy();
    $verbinding = null;
echo "<script>location.href='index.php'</script>";
?>
