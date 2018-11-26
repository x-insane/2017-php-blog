<?php
require_once( dirname(__FILE__) . "/config.php");
require_once( dirname(__FILE__) . "/functions.php");
global $uid;
global $blog_db;
global $path_blog;
global $file;
global $the_cat;
$file = 'post-passage';
if(!$uid)
	blog_die(701);
if(!(getlevel($uid) & 1))
	blog_die(751);
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	if(isset($_POST["rid"]))
	{
		if(is_passage_release_id($_POST["rid"]))
			$rid = $_POST["rid"];
		else
			blog_die(801);
	}
	else if(isset($_POST["pid"]))
	{
		if(is_passage_id($_POST["pid"]))
			$rid = get_passage_release_id($_POST["pid"]);
		else
			blog_die(802);
	}
	$title = addslashes($_POST["title"]);
	if($title == "")
		blog_die(752);
	$category = $_POST["category"];
	if(!$the_cat->is($_POST["category"]))
		blog_die(754);
	$content = addslashes($_POST["content"]);
	if($content == "")
		blog_die(753);
	if(!isset($rid))
		$rid = create_passage($uid);
	post_passage($rid,$uid,$title,$content,$category);
	exit();
}
if(isset($_GET["rid"]))
{
	if(is_passage_release_id($_GET["rid"]))
		$rid = $_GET["rid"];
	else
		blog_die(801);
}
else if(isset($_GET["p"]))
{
	if(is_passage_id($_GET["p"]))
		$pid = $_GET["p"];
	else
		blog_die(404);
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php
	 	if(isset($rid) || isset($pid))
	 		echo "修改文章";
	 	else
	 		echo "发布新文章";
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
		<div class="post-wrap">
		<article>
			<h2><?php
		 	if(isset($rid) || isset($pid))
		 		echo "修改文章";
		 	else
		 		echo "发布新文章";
			?></h2>
			<?php blog_info(); ?>
			<form action="post-passage.php" method="post">
				<?php
				if(isset($rid))
				{
					$pid = getpassageid($rid);
					?>
					<input type="hidden" name="rid" value=<?php
						echo $rid; ?>>
					<?php
				}
				else if(isset($pid))
				{
					?>
					<input type="hidden" name="pid" value=<?php
						echo $pid; ?>>
					<?php
				}
				if(isset($pid))
				{
					?>
					<div>
						<label>标题</label>
						<input type='text' name='title' value=<?php
							echo '"'.htmlspecialchars(getpassagetitle($pid)).'"';?>>
					</div>
					<textarea name="content"><?php
						echo getpassagecontent($pid); ?></textarea>
					<?php
				}
				else
				{
					?>
					<div>
						<label>标题</label>
						<input type="text" name="title">
					</div>
					<textarea name="content"></textarea>
					<?php
				}
				?>
				<div class="category-frame" id="catfrm">
				<?php
				if(isset($pid))
					$the_cat->get("radio",0,getpassagecategory($pid));
				else
					$the_cat->get("radio");
				?>
				</div>
				<div>
					<div id="addcatctrlfrm">
						<a href="javascript:onaddcatfrm()">＋添加分类</a>
					</div>
					<div id="addcatfrm" class="hidden">
						<div>新分类名<input type="text" id="newcatname"></div>
						<div>上级分类<select id="newcatparent">
							<option value="0">顶级分类</option>
							<?php $the_cat->getoption(); ?>
						</select></div>
						<div>
							<a href="javascript:addnewcat()">添加并选择</a>
							<a href="javascript:cancelnewcat()">取消</a>
						</div>
					</div>
				</div>
				<input type="submit">
			</form>
		</article>
		</div>
	</main>
	</div>
	</div>
	<?php blog_footer(); ?>
	<?php blog_script(); ?>
	<script src="<?php echo $path_blog; ?>/js/post.js"></script>
</body>
</html>