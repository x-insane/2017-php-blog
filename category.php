<?php
require_once( dirname(__FILE__) . "/config.php");
require_once( dirname(__FILE__) . "/functions.php");
global $file,$the_cat,$path_blog,$level,$path_user;
$file = 'category';
if(isset($level))
{
	if(($level & 7) == 0)
		blog_die(755);
}
else
{
	$_SESSION["url"] = $path_blog."/category.php";
	header("Location:$path_user/login.html");
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>分类管理</title>
	<link rel="stylesheet" type="text/css" href="global.css">
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"> -->
</head>
<body>
	<?php blog_header(); ?>
	<div class="main-wrap">
		<div id="content">
		<?php blog_sidebar(); ?>
			<main>
			<div class="category-wrap">
				<article>
					<h2>分类管理</h2>
					<p>选择一个分类来对其操作</p>
					<div class="category-frame" id="catfrm">
					<?php $the_cat->get("manage",0,1,1); ?>
					</div>
					<div id="cat-ctrl" class="hidden"><br>
						<div id="delframe">
							<span>删除分类</span>
							<span id="forcedeltip" style="color: red" class="hidden">该分类非空，删除会使该分类下所有文章变成未分类(包括子分类)</span>
							<a id="a-del" href="javascript:del()">确认删除</a>
							<a id="a-forcedel" href="javascript:forcedel()" class="hidden">强制删除</a>
						</div>
						<div id="moveframe">
							<span>移动到</span>
							<select id="movesel"></select>
							<a href="javascript:move()">确定</a>
						</div>
						<div>
							重命名 <input type="text" id="newname">
							<a href="javascript:rename()">确定</a>
						</div>
					</div>
					<h3>新建分类</h3>
					<div id="addcatfrm">
						<div>新分类名<input type="text" id="newcatname"></div>
						<div>上级分类<select id="newcatparent">
							<option value="0">顶级分类</option>
							<?php $the_cat->getoption(); ?>
						</select></div>
						<div>
							<a href="javascript:add()">添加</a>
						</div>
					</div>
				</article>
			</div>
			</main>
		</div>
	</div>
	<?php blog_footer(); ?>
	<?php blog_script(); ?>
	<script src="<?php echo $path_blog; ?>/js/category.js"></script>
</body>
</html>