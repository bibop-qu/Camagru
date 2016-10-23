<?php
require_once("config/database.php");
$pdo = new PDO($DB_HOST, $DB_USER, $DB_PASSWORD);
$req = $pdo->prepare('create database if not exists db_camagru');
if ($req->execute([]))
{
	$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	require('config/setup.php');
}
else
	$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
?>