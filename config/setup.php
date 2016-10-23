<?php
	require_once("./config/database.php");

	$db = new PDO($DB_HOST, $DB_USER, $DB_PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$query = $db->prepare('CREATE DATABASE IF NOT EXISTS db_camagru');
	$query->execute();
	$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);

	try {
		$querry = $db->prepare("CREATE TABLE IF NOT EXISTS tb_users (
			id INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			login varchar(255) NOT NULL, 
			password varchar(255) NOT NULL, 
			mail varchar(255) NOT NULL,
			token varchar(255));");
		$querry->execute();
	}
	catch(PDOException $e) {
      $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
      die($msg);
    }

	try {
		$querry = $db->prepare("CREATE TABLE IF NOT EXISTS tb_posts (
			id INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			owner varchar(255) NOT NULL,
			posts varchar(255) NOT NULL);");
		$querry->execute();
	}
	catch(PDOException $e) {
      $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
      die($msg);
    }

	try {
		$querry = $db->prepare("CREATE Table IF NOT EXISTS tb_likes (
			id INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			login varchar(255) NOT NULL,
			posts varchar(255) NOT NULL);");
		$querry->execute();
	}
	catch(PDOException $e) {
      $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
      die($msg);
    }

	try {
		$querry = $db->prepare("CREATE Table IF NOT EXISTS tb_comments (
			id INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			login varchar(255) NOT NULL,
			posts varchar(255) NOT NULL,
			content varchar(2048) NOT NULL,
			t timestamp default NOW());");
		$querry->execute();
	}
	catch(PDOException $e) {
      $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
      die($msg);
    }

?>
