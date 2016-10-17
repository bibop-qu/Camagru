<?php session_start(); ?>
<html>
	<head>
		<title>Camagru</title>
		<meta charset="UTF-8">
		<meta name="author" content="basle-qu">
		<meta name="description" content="instagram-like">
		<link rel="stylesheet" type="text/css" href="style/design.css">
	</head>
	<body>
		<header>
			<h1 class="titre">Camagru <?php include 'nav.php'; ?></h1>
		</header>
		<section>
			<?php
			if ($_SESSION['login'])
				echo 'Hello '. $_SESSION['login'];
			else
				echo Home;
			?>
		</section>
		<footer>@basle-qu</footer>
	</body>
</html>