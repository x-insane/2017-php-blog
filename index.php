<?php
require_once( dirname(__FILE__) . "/config.php");
require_once( dirname(__FILE__) . "/functions.php");		
global $file,$the_cat;
if(isset($_GET['r']))
{
	if(!is_passage_release_id($_GET['r']))
		blog_die(801);
	$file = 'single';
	$pid = getpassageid($_GET['r']);
}
else if(isset($_GET['p']))
{
	if(!is_passage_id($_GET['p']))
		blog_die(404);
	$file = 'single';
	$pid = $_GET['p'];
}
else
	$file = 'all';
?>
<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<title><?php
	if($file == 'single')
		echo getpassagetitle($pid);
	else
		echo $conf["title"];
	?></title>
	<link rel="stylesheet" type="text/css" href="global.css">
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"> -->
</head>
<body>
	<?php blog_header(); ?>
	<div class="main-wrap">
	<div id="content">
		<?php blog_sidebar(); ?>
		<main>
		<?php
		if(blog_info())
		{
			if(isset($pid))
			{
				?>
				<div class="s-passage-wrapper">
				<?php
				if(!getpassage($pid))
					blog_die(404);
				?>
				</div>
				<?php
			}
			else if(isset($_GET["c"]) && $the_cat->is($_GET["c"]))
			{
				?>
				<div class="passages-wrapper">
					<article style="width: 100%;">
						<h2><a>分类目录: <?php $the_cat->getname($_GET["c"]); ?></a></h2>
					</article>
					<?php getofcategory($_GET["c"]); ?>
				</div>
				<?php
			}
			else
			{
				?>
				<div class="passages-wrapper">
				<?php
				getAllpassages();
				?>
				</div>
				<?php
			}
		}	
		?>
		</main>
	</div>
	</div>
	<?php blog_footer(); ?>
	<?php blog_script(); ?>
</body>
</html>