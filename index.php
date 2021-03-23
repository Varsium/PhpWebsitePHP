<link rel="stylesheet" href="CSS/formulier.css">
<?php

if(isset($_GET["page"])) {
    $page = $_GET["page"];
    if (($page=="wachtwoord_vergeten")||($page=="registreren")){
    }else{include ("header.html");}
} else {
    $page = "inloggen";
}
if($page) {

    include("pages/" . $page . ".php");
}
?>
<?php
?>
