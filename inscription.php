<?php

include 'config/database.php';

	if (!$_POST['login'] || !$_POST['passwd'] || !$_POST['repasswd'] || !$_POST['email'] || $_POST['submit'] !== "OK")
	   exit("ERROR\n");

if ($_POST['passwd'] != $_POST['repasswd'])
	header("location: register.php");

try {
//		echo "test"."\n";
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
  $mail = $_POST['email'];

  $query = 'SELECT * FROM users WHERE login=?;';
  $prep = $db->prepare($query);
  $prep->bindValue(1, $log, PDO::PARAM_STR);
  $prep->execute();
  if($prep->rowCount() > 0)
  	header("location: register.php");
  $prep->closeCursor();
  $prep = NULL;


  $query = 'SELECT * FROM users WHERE email=?;';
  $prep = $db->prepare($query);
  $prep->bindValue(1, $mail, PDO::PARAM_STR);
  $prep->execute();
  if($prep->rowCount() > 0)
  	header("location: register.php");
  $prep->closeCursor();
  $prep = NULL;

  $query = 'INSERT INTO users (login, password, email) VALUES (?, ?, ?);';
  $prep = $db->prepare($query);
  $prep->bindValue(1, $log, PDO::PARAM_STR);
  $prep->bindValue(2, $pass, PDO::PARAM_STR);
  $prep->bindValue(3, $mail, PDO::PARAM_STR);
  $prep->execute();
  $prep->closeCursor();
  $prep = NULL;
  
echo "YESf";
?>