<?php

class View
{
	public function header($data)
	{
		require("views/header.php");
	}

	public function home()
	{
		require("views/home.html");
	}

	public function create()
	{
		require("views/create.php");
	}

	public function profile($data, $posts)
	{
		require("views/profile.php");
	}

	public function main($data, $posts)
	{
		require("views/main.php");
	}

	public function gallery($data, $page)
	{
		require("views/gallery.php");
	}

	public function fargot_password()
	{
		require("views/fargot_password.html");
	}

}	

?>
