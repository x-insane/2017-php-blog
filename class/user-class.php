<?php
class User extends mysql_user
{
	function __construct()
	{
		parent::__construct();
		global $path_root;
		$_SESSION['url'] = $path_root.$_SERVER['REQUEST_URI'];
	}
	function user_sign_list()
	{
		global $path_user,$path_blog,$level,$file;
		if(!isset($level))
		{
			?>
			<ul>
				<li><a href="<?php echo $path_user; ?>/login.html">登陆</a></li>
				<li><a href="<?php echo $path_user; ?>/register.html">注册</a></li>
			</ul>
			<?php
		}
		else
		{
			?>
			<ul>
				<li><a href="javascript:logout('<?php
				echo $path_user ?>/logout.php','<?php
				echo $path_blog ?>')">退出登录</a></li>
				<?php
				if($level & 7 && $file != 'category')
				{
				?>
					<li><a href="<?php echo $path_blog; ?>/category.php">分类管理</a></li>
				<?php
				}
				if($level & 15 && $file != 'media')
				{
				?>
					<li><a href="<?php echo $path_blog; ?>/media.php">素材管理</a></li>
				<?php
				}
				?>
			</ul>
			<?php
		}
	}
}