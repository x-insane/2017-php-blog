<?php
require_once( dirname(__FILE__) . "/config.php");
require_once( dirname(__FILE__) . "/functions.php");
global $the_cat,$level;
$file = 'post-passage';
if(!isset($level))
	blog_die(701);
if(!($level & 15))
	blog_die(756);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>素材管理</title>
	<link rel="stylesheet" type="text/css" href="global.css">
	<link rel="stylesheet" type="text/css" href="css/media.css">
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"> -->
</head>
<body>
	<?php blog_header(); ?>
	<div class="main-wrap">
	<div id="content">
		<?php blog_sidebar(); ?>
		<main>
			<div class="upload-wrap">
				<article>
					<form id="fileform" action="">
						<input type="file" accept="image/jpeg,image/png,image/gif" name="file">
						<img src="" id="img">
						<input type="submit" id="submit">
					</form>
				</article>
			</div>
			<div class="media-wrap">
			<?php getuploadfiles(); ?>
			</div>
		</main>
	</div>
	</div>
	<?php blog_footer(); ?>
	<?php blog_script(); ?>
<script>
	document.getElementById("submit").onclick = function(e)
	{
		if(e.preventDefault)
			e.preventDefault();
		else
			e.returnValue = false;
		var formdata = new FormData(document.getElementById("fileform"));
		var req = new XMLHttpRequest();
        req.open("POST", "api/file/upload.php");
   		req.onload = function()
   		{
			if(this.status === 200)
			{
				// document.getElementById("img").src = this.response;
				location.reload(true);
			}
		}
		req.send(formdata);
	}
</script>
</body>
</html>