<html>
<html>
	<head>
		<title>MAIN</title>
		<meta charset="UTF-8">
		<meta name="author" content="basle-qu">
		<meta name="description" content="MAIN">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="style/design.css">
	</head>
	<body>
		<h1>Main page</h1>
		
		<div class="main">
		<device type="media" onchange="update(this.data)"></device>
		<div class="display">
			<div class="preview">
				<video class="preview-cam" autoplay="true" id="video"></video>
				<?php if(isset($_GET["filter"])) { ?>
					<img class="preview-filter" src="filters/<?php echo $_GET["filter"]; ?>"/>
				<?php } ?>
			</div>
			<div class="preview preview-canvas" id="pic" style="display: none;">
				<canvas class="preview-cam" id="canvas"></canvas>
				<?php if(isset($_GET["filter"])) { ?>
					<img class="preview-filter" src="filters/<?php echo $_GET["filter"]; ?>"/>
				<?php } ?>
			</div>
		</div>

 		<div class="filters">
			<?php
				foreach($data as $elem)
				{
			?>
						<?php if ($elem == $_GET['filter']) { ?>
							<img class="filters-preview" style="border: 1px solid red" src="<?php echo "filters/".$elem; ?>"/>
						<?php } else { ?>
						<a href="index.php?page=main&filter=<?php echo $elem; ?>">
							<img class="filters-preview" src="<?php echo "filters/".$elem; ?>">
						</a>
						<?php } ?>
			<?php
				}
			?>	
		</div>
		<br/>
		<button id="video_capture" onclick="new_pic()">Clear</button>
		<button id="video_capture" onclick="snap()">Take a picture</button></br>

		<form action="index.php?page=upload" method="post" enctype="multipart/form-data">
			<input id="img" type="hidden" name="img" value="">

			<input type="file" accept="image/*" name="picture" onchange="load(event)"></input><br/>
			<img id="prev"/>
			<script>
				var load = function(event){
				var prev = document.getElementById('prev');
				if (event.target.files[0] != null) {
					prev.src = URL.createObjectURL(event.target.files[0]);
				}
				else
				{
					console.log('coucou y a pas d\'erreur haha <3');
				}
				}
			</script>

			<input type="hidden" name="filter" value="<?php if (isset($_GET['filter'])) {echo $_GET['filter'];} else {echo "void";} ?>">
			<?php if (!isset($_GET['filter']) || $_GET['filter'] == "") { ?>
				<p>Select one filter to submit</p>
			<?php } else { ?>
			<br/>
			<input type="submit" value="submit" />
			<?php } ?>
		</form>
		</div>

		<div class="side">
			<?php foreach($posts as $post) { ?>
				<a href="index.php?page=remove_post&post=<?php echo $post['posts']; ?>">
				<img src="<?php echo $post['posts']?>"/>
				</a>
			<?php } ?>		
		</div>

		<script>
			navigator.getUserMedia = ( navigator.getUserMedia ||
						navigator.webkitGetUserMedia ||
					  	navigator.msGetUserMedia);

			var reg = /^.*Firefox.*/
			if (navigator.userAgent.match(reg) != null)
			{
				navigator.mediaDevices.getUserMedia({video: true}).then(
					function (stream) {
						var video = document.querySelector('video');
						video.src = window.URL.createObjectURL(stream);
						video.play();
					},
					function (){
						alert("Can't open video stream. :(");	
					});
			}
			else if (navigator.getUserMedia)
			{
				navigator.getUserMedia(
					{video: true},
					function(stream)
					{
						var video = document.querySelector('video');
						video.src = window.URL.createObjectURL(stream);
						video.play();
					},
					function()
					{
						alert("Cannot open video stream.");
					}
				);
			}

			function snap()
			{
				var canvas = document.querySelector('#canvas');
				var video = document.querySelector('video');
				var snap = document.querySelector('#snap');
				document.querySelector('#pic').style.display = 'block';
				canvas.width = 320;
				canvas.height = 240;
				canvas.getContext('2d').drawImage(video, 0, 0, 320, 240);
				var data = canvas.toDataURL("image/png");
				var tab = data.split(',');
				console.log(tab);
				var img = document.querySelector("#img");
				img.value = tab[1];
			}

			function new_pic()
			{
				var canvas = document.querySelector('#canvas');
				canvas.getContext('2d').clearRect(0, 0, 320, 240);
				document.querySelector('#pic').style.display = 'none';
				var input = document.querySelector('#img');
				img.value = "";
			}
		</script>
	</body>
</html>
