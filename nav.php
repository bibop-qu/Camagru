<?php session_start(); ?>
<hr>
<nav>
	<a title="Accueil" href="./index.php">HOME</a>
	<a title="Gallerie" href="./gallerie.php">GALLERY</a>
	<?php
		if (!$_SESSION['login'])
			echo "<a HREF='./sign_in.php'>SIGN IN</a>";
		else
			echo "<a href='./sign_out.php'>SIGN OUT</a>";
	?>
</nav>