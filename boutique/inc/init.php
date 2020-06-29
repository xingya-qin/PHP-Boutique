<?php

// Connexion à la BDD
$pdo = new PDO('mysql:host=localhost;dbname=boutique','root','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

// variable globale
$content = "";

// Ouverture d'une session
session_start();

//	d�finition de constante
define("RACINE_SITE", $_SERVER['DOCUMENT_ROOT'] . '/php/boutique/');
define("URL", "http://" . $_SERVER['HTTP_HOST'] . "/php/boutique/");

require_once("fonction.php");
?>