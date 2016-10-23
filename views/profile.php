<!doctype html>

<html>
	<head>
		<title>PROFILE</title>
		<meta charset="UTF-8">
		<meta name="author" content="basle-qu">
		<meta name="description" content="PROFILE">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="style/design.css">
	</head>
	<body>
		<div class="page-content">
			<p><?php echo htmlspecialchars($data["login"]) . "'s ";?>profile</p><br/>

			<div class="update-pwd">
				<h3>Change your password</h3>
				<form method="post" action="index.php?page=pwd_change">
					<p>Actual password</p>
					<input type="password" name="old"/>
					<p>New password</p>
					<input type="password" name="new"/>
					<p>New password (confirmation)</p>
					<input type="password" name="confirmation"/><br/>
					<input type="submit" value="submit"/>
				</form>
			</div>
			Email: <?php echo htmlspecialchars($data["mail"]); ?><br/>
		</div>
	</body>
</html>
