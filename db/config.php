<?php
$host = "mysql-joelvegasromero.alwaysdata.net";
$user = "439220";
$passwd = "Ju94714016*";
$db_name = "joelvegasromero_bluewave_hotels";

$mysqli = new mysqli($host,$user,$passwd,$db_name);
if($mysqli -> connect_errno){
    die("ERROR AL CONECTARSE AL DB");
}
