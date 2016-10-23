<?php
session_start();
//equire_once("./config/setup.php");
require_once("lib/db_connect.php");
require_once("lib/Controller.class.php");
$ctl = new Controller();
if (!isset($_GET["page"]))
{
	$ctl->home();
}
else
{
	$page = $_GET['page'];
	if (method_exists($ctl, $page) == true)
		$ctl->$page();
	else
		echo "invalid page";
}
?>
