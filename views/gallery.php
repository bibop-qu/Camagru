<?php global $ctl; ?>


<html>
	<head>
		<title>GALLERY</title>
		<meta charset="UTF-8">
		<meta name="author" content="basle-qu">
		<meta name="description" content="GALLERY">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="style/design.css">
	</head>
	<body>
		<div class="posts page-content">
		<?php
		foreach($data as $elem)
		{?>
			<div class="post" id="<?php echo  "div" . $elem['posts']; ?>">
				<img src="<?php echo htmlspecialchars($elem['posts']); ?>" /><br/>
				<div>
					Owner: <?php echo htmlspecialchars($elem['owner']); ?><br/>
					Likes: <p id="<?php echo htmlspecialchars($elem['posts']); ?>"><?php echo $ctl->get_like($elem['posts']); ?></p>
				<?php
				if ($ctl->get_like_user($elem['posts']) == 0) {
				?>
					<button id="<?php echo  "btn" . htmlspecialchars($elem['posts']); ?>" onclick="like_it('<?php echo $elem['posts']; ?>')">Do you like it?</button>
					</div>
				<?php
				}
				else {
				?>
					<p>You already like this picture :D</p>
					</div>
				<?php
				}
				?>
				<?php
				$comments = $ctl->get_comments($elem['posts']);
				foreach($comments as $comment) {	
				?>
					<div class="comment">
						<hr/>
						<div class="comment-info">
							<p class="comment-login"><?php echo htmlspecialchars($comment['login']); ?></p>
							<p class="comment-date"><?php echo htmlspecialchars($comment['t']); ?></p>
						</div>
						<br/>
						<p class="comment-content"><?php echo htmlspecialchars($comment['content']); ?></p>
					</div>
				<?php
				}
				?>
				<form action="index.php?page=comment" method="post">
					<input type="hidden" name="post" value="<?php echo $elem['posts']; ?>">
					Comment: <input type="text" name="content">
					<input type="submit" value="Send">
				</form>
			</div>
		<?php
		}?>
		</div>

		<div class="link">
		<?php 
		$i = 0;
		while ($i <= $page)
		{ ?>
			<a href="index.php?page=gallery&number=<?php echo $i; ?>" ><?php echo $i; ?></a>
		
		<?php
			$i++;
		} ?>
		</div>
		<script>
			function like_it(post)
			{
				var xhr = new XMLHttpRequest();
				var pst = encodeURIComponent(post);

				xhr.onreadystatechange = function(){
					if (xhr.readyState == 4)
					{
						var nbr = document.getElementById(post);
						nbr.innerHTML = parseInt(nbr.innerHTML) + 1;
						document.getElementById("btn" + post).remove();
						var p = document.createElement("p");
						p.innerHTML = "You already like this picture :D";
						document.getElementById("div" + post).appendChild(p);
					}
				}
				xhr.open("GET", "index.php?page=like&post=" + pst, true);
				xhr.send();
			}
		</script>
	</body>
</html>
