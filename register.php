<html>
	<head>
		<title>REGISTER</title>
		<meta charset="UTF-8">
		<meta name="author" content="basle-qu">
		<meta name="description" content="Sign in">
		<link rel="stylesheet" type="text/css" href="style/design.css">
	</head>
	<body>
		<header>
			<h1 class="titre">Register <?php include 'nav.php'; ?></h1>
		</header>
		<section>
				<form class="tform" action="" method="POST">
				<header>LOG IN</header><br/>
				<label>IDENTIFIANT</label>
				<input id=inp type="text" name="login" placeholder="Login"/><br/>
				<label>MOT DE PASSE</label>
				<input id=inp type="password" name="passwd" placeholder="Password"/><br/>
				<input id=sub type="submit" name="submit" value="OK"/>
				</form>
		</section>
		<footer>@basle-qu</footer>
	</body>
</html>