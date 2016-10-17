<?php
include 'config/database.php';

if (!$_POST['login'] || !$_POST['passwd'] || $_POST['submit'] !== "OK")
	exit("ERROR\n");

try {
	$db=new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	// set the PDO error mode to exception
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
    $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
    die($msg);
}

$pass = hash('whirlpool', $_POST['passwd']);
$log = $_POST['login'];

  $query = 'SELECT * FROM users WHERE login=? AND password=?;';
  $prep = $db->prepare($query);
  $prep->bindValue(1, $log, PDO::PARAM_STR);
  $prep->bindValue(2, $pass, PDO::PARAM_STR);
  $prep->execute();
  if($prep->rowCount() > 0)
  {
  	session_start();
  	$_SESSION['login'] = $log;
  	header("location: index.php");
  }
  else
  	header("location: sign_in.php");
?>